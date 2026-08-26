import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.testobject.ConditionType as ConditionType
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import org.openqa.selenium.Keys as Keys

// ============================================================
// HELPER: Buat TestObject dari XPath (tanpa Object Repository)
// ============================================================
def xpath(String expr) {
    TestObject obj = new TestObject('xpath_' + expr.hashCode())
    obj.addProperty('xpath', ConditionType.EQUALS, expr)
    return obj
}

// Helper: Safe Click dengan Scroll & Fallback
def safeClick(TestObject to) {
    if (WebUI.verifyElementPresent(to, 5, FailureHandling.OPTIONAL)) {
        try {
            WebUI.scrollToElement(to, 3, FailureHandling.OPTIONAL)
            WebUI.delay(1)
            WebUI.enhancedClick(to, FailureHandling.OPTIONAL)
        } catch (Exception e) {
            WebUI.click(to, FailureHandling.OPTIONAL)
        }
    }
}

// Helper: Logout aman dengan clear session cookies (hindari error 405)
def doLogout(String baseUrl) {
    WebUI.deleteAllCookies()
    WebUI.delay(1)
    WebUI.navigateToUrl(baseUrl + "/login")
    WebUI.waitForElementVisible(xpath("//input[@name='email']"), 10, FailureHandling.OPTIONAL)
}

// Helper: login ke aplikasi
def doLogin(String email, String password) {
    WebUI.waitForElementVisible(xpath("//input[@name='email']"), 15, FailureHandling.OPTIONAL)
    WebUI.clearText(xpath("//input[@name='email']"))
    WebUI.setText(xpath("//input[@name='email']"), email)
    WebUI.clearText(xpath("//input[@name='password']"))
    WebUI.setText(xpath("//input[@name='password']"), password)
    WebUI.click(xpath("//button[@type='submit']"))
    WebUI.delay(3)
}

// Helper: verifikasi URL mengandung path tertentu
def assertUrlContains(String expected) {
    String url = WebUI.getUrl()
    assert url.contains(expected) : "URL harus mengandung '${expected}' | URL aktual: ${url}"
    WebUI.comment("URL OK: " + url)
}

// ============================================================
String BASE_URL  = "http://103.89.4.245"
String SA_EMAIL  = "superadmin@mandalajati.com"   // super_admin   -> /superadmin
String ADM_EMAIL = "admin@mandalajati.com"         // admin_kecamatan -> /admin
String OPS_EMAIL = "operator1@mandalajati.com"     // operator_lapangan -> /operator
String PLK_EMAIL = "ikhsanocta12@gmail.com"        // pelaku_umkm  -> /pelaku
String PASS      = "password"

// ============================================================================
// TC1: VERIFIKASI LOGIN MULTI-ROLE
// ============================================================================
WebUI.comment("========== TC1: LOGIN MULTI-ROLE ==========")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)

// TC1.1 - Super Admin
WebUI.comment("TC1.1: Super Admin")
WebUI.navigateToUrl(BASE_URL + "/login")
doLogin(SA_EMAIL, PASS)
assertUrlContains('/superadmin')
WebUI.comment("TC1.1 PASSED")
doLogout(BASE_URL)

// TC1.2 - Admin Kecamatan
WebUI.comment("TC1.2: Admin Kecamatan")
doLogin(ADM_EMAIL, PASS)
assertUrlContains('/admin')
WebUI.comment("TC1.2 PASSED")
doLogout(BASE_URL)

// TC1.3 - Operator Lapangan
WebUI.comment("TC1.3: Operator Lapangan")
doLogin(OPS_EMAIL, PASS)
assertUrlContains('/operator')
WebUI.comment("TC1.3 PASSED")
doLogout(BASE_URL)

// TC1.4 - Pelaku UMKM
WebUI.comment("TC1.4: Pelaku UMKM")
doLogin(PLK_EMAIL, PASS)
assertUrlContains('/pelaku')
WebUI.comment("TC1.4 PASSED")

WebUI.closeBrowser()

// ============================================================================
// TC2: SUPER ADMIN — MANAJEMEN DATA UMKM
// ============================================================================
WebUI.comment("========== TC2: SUPER ADMIN MANAGEMENT ==========")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)
WebUI.navigateToUrl(BASE_URL + "/login")
doLogin(SA_EMAIL, PASS)
assertUrlContains('/superadmin')

// 2.1 Daftar UMKM
WebUI.navigateToUrl(BASE_URL + "/superadmin/umkm")
WebUI.delay(2)
assertUrlContains('/superadmin/umkm')

// 2.2 Filter / Pencarian AJAX
if (WebUI.verifyElementPresent(xpath("//input[@name='search']"), 5, FailureHandling.OPTIONAL)) {
    WebUI.setText(xpath("//input[@name='search']"), 'Laundry')
    WebUI.sendKeys(xpath("//input[@name='search']"), Keys.chord(Keys.ENTER))
    WebUI.delay(3)
}

// 2.3 Buka halaman detail UMKM
WebUI.navigateToUrl(BASE_URL + "/superadmin/umkm")
WebUI.delay(2)
def detailLinkSA = xpath("(//a[contains(@href,'/superadmin/umkm/') and contains(@href,'/show')])[1]")
if (WebUI.verifyElementPresent(detailLinkSA, 5, FailureHandling.OPTIONAL)) {
    safeClick(detailLinkSA)
    WebUI.delay(2)
    assertUrlContains('/superadmin/umkm/')
}

// 2.4 Verifikasi Akun
WebUI.navigateToUrl(BASE_URL + "/superadmin/verifikasi-akun")
WebUI.delay(2)
assertUrlContains('/superadmin/verifikasi-akun')

// 2.5 User Management
WebUI.navigateToUrl(BASE_URL + "/superadmin/users")
WebUI.delay(2)
assertUrlContains('/superadmin/users')

// 2.6 Data Master Kelurahan
WebUI.navigateToUrl(BASE_URL + "/superadmin/kelurahan")
WebUI.delay(2)
assertUrlContains('/superadmin/kelurahan')

// 2.7 Data Master Sektor
WebUI.navigateToUrl(BASE_URL + "/superadmin/sektor")
WebUI.delay(2)
assertUrlContains('/superadmin/sektor')

// 2.8 Pengajuan Bantuan
WebUI.navigateToUrl(BASE_URL + "/superadmin/pengajuan")
WebUI.delay(2)
assertUrlContains('/superadmin/pengajuan')

WebUI.closeBrowser()

// ============================================================================
// TC3: ADMIN KECAMATAN — VERIFIKASI LAPANGAN & LAPORAN
// ============================================================================
WebUI.comment("========== TC3: ADMIN KECAMATAN ==========")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)
WebUI.navigateToUrl(BASE_URL + "/login")
doLogin(ADM_EMAIL, PASS)
assertUrlContains('/admin')

// 3.1 Daftar UMKM Admin
WebUI.navigateToUrl(BASE_URL + "/admin/umkm")
WebUI.delay(2)
assertUrlContains('/admin/umkm')

// 3.2 Verifikasi Lapangan
WebUI.navigateToUrl(BASE_URL + "/admin/verifikasi")
WebUI.delay(2)
assertUrlContains('/admin/verifikasi')

// 3.3 Buka detail verifikasi UMKM
def detailLinkAdm = xpath("(//a[contains(@href,'/admin/verifikasi/') and not(contains(@href,'create'))])[1]")
if (WebUI.verifyElementPresent(detailLinkAdm, 5, FailureHandling.OPTIONAL)) {
    safeClick(detailLinkAdm)
    WebUI.delay(2)
    assertUrlContains('/admin/verifikasi/')
}

// 3.4 Rekap & Laporan
WebUI.navigateToUrl(BASE_URL + "/admin/laporan")
WebUI.delay(2)
assertUrlContains('/admin/laporan')

// 3.5 Verifikasi Akun
WebUI.navigateToUrl(BASE_URL + "/admin/verifikasi-akun")
WebUI.delay(2)
assertUrlContains('/admin/verifikasi-akun')

WebUI.closeBrowser()

// ============================================================================
// TC4: OPERATOR LAPANGAN — INPUT DATA UMKM BARU
// ============================================================================
WebUI.comment("========== TC4: OPERATOR LAPANGAN ==========")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)
WebUI.navigateToUrl(BASE_URL + "/login")
doLogin(OPS_EMAIL, PASS)
assertUrlContains('/operator')

// 4.1 Daftar UMKM Operator
WebUI.navigateToUrl(BASE_URL + "/operator/umkm")
WebUI.delay(2)
assertUrlContains('/operator/umkm')

// 4.2 Form Input UMKM Baru
WebUI.navigateToUrl(BASE_URL + "/operator/umkm/create")
WebUI.delay(2)
assertUrlContains('/operator/umkm/create')

// 4.3 Isi form
String ts = "" + System.currentTimeMillis()

if (WebUI.verifyElementPresent(xpath("//input[@name='nik']"), 5, FailureHandling.OPTIONAL)) {
    WebUI.setText(xpath("//input[@name='nik']"), '3273012345670001')
    WebUI.setText(xpath("//input[@name='nama_lengkap']"), 'Budi Santoso Test')
    WebUI.selectOptionByValue(xpath("//select[@name='jenis_kelamin']"), 'L', false)
    WebUI.setText(xpath("//input[@name='tempat_lahir']"), 'Bandung')
    WebUI.setText(xpath("//input[@name='tanggal_lahir']"), '1990-01-01')
    WebUI.setText(xpath("//input[@name='no_hp']"), '081234567890')
    WebUI.setText(xpath("//input[@name='nama_usaha']"), 'UMKM Katalon ' + ts)
    
    if (WebUI.verifyElementPresent(xpath("//select[@name='id_sektor']"), 3, FailureHandling.OPTIONAL)) {
        WebUI.selectOptionByIndex(xpath("//select[@name='id_sektor']"), 1)
    }
    if (WebUI.verifyElementPresent(xpath("//textarea[@name='alamat_pemilik']"), 3, FailureHandling.OPTIONAL)) {
        WebUI.setText(xpath("//textarea[@name='alamat_pemilik']"), 'Jl. Mandalajati No. 45')
    }
    if (WebUI.verifyElementPresent(xpath("//textarea[@name='alamat_usaha']"), 3, FailureHandling.OPTIONAL)) {
        WebUI.setText(xpath("//textarea[@name='alamat_usaha']"), 'Jl. Mandalajati No. 45')
    }
}

// 4.4 Verifikasi Lapangan Operator
WebUI.navigateToUrl(BASE_URL + "/operator/verifikasi")
WebUI.delay(2)
assertUrlContains('/operator/verifikasi')

WebUI.closeBrowser()

// ============================================================================
// TC5: PELAKU UMKM — PROFIL, PRODUK & PENGAJUAN
// ============================================================================
WebUI.comment("========== TC5: PELAKU UMKM ==========")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)
WebUI.navigateToUrl(BASE_URL + "/login")
doLogin(PLK_EMAIL, PASS)
assertUrlContains('/pelaku')

// 5.1 Dashboard
WebUI.navigateToUrl(BASE_URL + "/pelaku/dashboard")
WebUI.delay(2)
assertUrlContains('/pelaku/dashboard')

// 5.2 Profil
WebUI.navigateToUrl(BASE_URL + "/pelaku/profil")
WebUI.delay(2)
assertUrlContains('/pelaku/profil')

// 5.3 Katalog Produk
WebUI.navigateToUrl(BASE_URL + "/pelaku/produk")
WebUI.delay(2)
assertUrlContains('/pelaku/produk')

// 5.4 Pengajuan Bantuan
WebUI.navigateToUrl(BASE_URL + "/pelaku/pengajuan")
WebUI.delay(2)
assertUrlContains('/pelaku/pengajuan')

// 5.5 Pelatihan
WebUI.navigateToUrl(BASE_URL + "/pelaku/pelatihan")
WebUI.delay(2)
assertUrlContains('/pelaku/pelatihan')

WebUI.closeBrowser()

WebUI.comment("========================================")
WebUI.comment("SEMUA TEST CASE SELESAI DIJALANKAN DENGAN SUKSES!")
WebUI.comment("========================================")
