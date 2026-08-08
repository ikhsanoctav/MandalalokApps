import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject

import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testng.keyword.TestNGBuiltinKeywords as TestNGKW
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

/**
 * ============================================================================
 * KATALON STUDIO AUTOMATION TEST SUITE - MANDALALOKA SYSTEM
 * Base URL: http://103.89.4.245
 * Target Coverage: Authentication, Super Admin, Admin Kecamatan, Petugas, Pelaku UMKM
 * ============================================================================
 */

String baseUrl = GlobalVariable.G_SiteUrl ?: "http://103.89.4.245"

// ============================================================================
// TEST CASE 1: VERIFIKASI LOGIN & MULTI-ROLE ACCESS CONTROL
// ============================================================================
WebUI.comment("--- TC1: LOGIN MULTI-ROLE ---")
WebUI.openBrowser('')
WebUI.setViewPortSize(1366, 768)
WebUI.navigateToUrl(baseUrl + "/login")

// 1.1 Login Valid - Super Admin
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'superadmin@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))

WebUI.verifyElementPresent(findTestObject('Object Repository/Dashboard/header_Mandalaloka'), 10)
WebUI.verifyTextPresent('Dashboard', false)
WebUI.comment("SUCCESS: Super Admin logged in successfully.")

// Logout Super Admin
WebUI.click(findTestObject('Object Repository/Topbar/button_ProfileDropdown'))
WebUI.click(findTestObject('Object Repository/Topbar/button_Logout'))
WebUI.verifyElementPresent(findTestObject('Object Repository/Page_Login/input_email'), 10)

// 1.2 Login Valid - Admin Kecamatan
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'admin@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))
WebUI.verifyTextPresent('Admin Kecamatan', false)
WebUI.comment("SUCCESS: Admin Kecamatan logged in successfully.")

// Logout Admin Kecamatan
WebUI.click(findTestObject('Object Repository/Topbar/button_ProfileDropdown'))
WebUI.click(findTestObject('Object Repository/Topbar/button_Logout'))

// 1.3 Login Valid - Petugas Lapangan
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'petugas@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))
WebUI.verifyTextPresent('Petugas Lapangan', false)
WebUI.comment("SUCCESS: Petugas Lapangan logged in successfully.")

// Logout Petugas Lapangan
WebUI.click(findTestObject('Object Repository/Topbar/button_ProfileDropdown'))
WebUI.click(findTestObject('Object Repository/Topbar/button_Logout'))

// 1.4 Login Valid - Pelaku UMKM
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'pelaku@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))
WebUI.verifyTextPresent('Profil Saya', false)
WebUI.comment("SUCCESS: Pelaku UMKM logged in successfully.")

WebUI.closeBrowser()

// ============================================================================
// TEST CASE 2: SUPER ADMIN - MANAGEMENT DATA UMKM & VERIFIKASI AKUN
// ============================================================================
WebUI.comment("--- TC2: SUPER ADMIN MANAGEMENT ---")
WebUI.openBrowser('')
WebUI.navigateToUrl(baseUrl + "/login")
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'superadmin@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))

// Navigasi ke Daftar UMKM
WebUI.navigateToUrl(baseUrl + "/superadmin/umkm")
WebUI.verifyTextPresent('Daftar UMKM', false)

// Test Filter & Pencarian UMKM
WebUI.setText(findTestObject('Object Repository/UMKM/input_search'), 'Laundry')
WebUI.sendKeys(findTestObject('Object Repository/UMKM/input_search'), Keys.chord(Keys.ENTER))
WebUI.delay(2)
WebUI.verifyTextPresent('Laundry', false)

// Test Buka Modal Detail UMKM & Lightbox Gambar
WebUI.click(findTestObject('Object Repository/UMKM/button_DetailFirstRow'))
WebUI.delay(2)
WebUI.verifyElementPresent(findTestObject('Object Repository/UMKM/modal_DetailUMKM'), 5)

// Klik Foto untuk memperbesar (Lightbox Preview)
WebUI.click(findTestObject('Object Repository/UMKM/img_CoverBanner'))
WebUI.delay(1)
WebUI.verifyElementPresent(findTestObject('Object Repository/Global/globalImageModal'), 5)
WebUI.click(findTestObject('Object Repository/Global/button_CloseImageModal'))

// Navigasi Verifikasi Akun Pemilik
WebUI.navigateToUrl(baseUrl + "/superadmin/verifikasi-akun")
WebUI.verifyTextPresent('Verifikasi Akun', false)

// Navigasi User Manajemen
WebUI.navigateToUrl(baseUrl + "/superadmin/users")
WebUI.verifyTextPresent('Daftar User', false)

// Navigasi Data Master Kelurahan & Sektor
WebUI.navigateToUrl(baseUrl + "/superadmin/kelurahan")
WebUI.verifyTextPresent('Data Kelurahan', false)

WebUI.navigateToUrl(baseUrl + "/superadmin/sektor")
WebUI.verifyTextPresent('Data Sektor', false)

WebUI.closeBrowser()

// ============================================================================
// TEST CASE 3: ADMIN KECAMATAN - VERIFIKASI LAPANGAN & REKAP LAPORAN
// ============================================================================
WebUI.comment("--- TC3: ADMIN KECAMATAN VERIFIKASI ---")
WebUI.openBrowser('')
WebUI.navigateToUrl(baseUrl + "/login")
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'admin@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))

// Navigasi ke Verifikasi Lapangan
WebUI.navigateToUrl(baseUrl + "/admin/verifikasi")
WebUI.verifyTextPresent('Verifikasi Lapangan', false)

// Buka Detail & Verifikasi UMKM
if (WebUI.verifyElementPresent(findTestObject('Object Repository/Verifikasi/button_DetailVerifyFirstRow'), 3, FailureHandling.OPTIONAL)) {
    WebUI.click(findTestObject('Object Repository/Verifikasi/button_DetailVerifyFirstRow'))
    WebUI.delay(2)
    WebUI.verifyElementPresent(findTestObject('Object Repository/Verifikasi/button_Setujui'), 5)
}

// Navigasi Rekap & Laporan
WebUI.navigateToUrl(baseUrl + "/admin/laporan")
WebUI.verifyTextPresent('Rekap & Laporan', false)
WebUI.verifyElementPresent(findTestObject('Object Repository/Laporan/button_ExportExcel'), 5)

WebUI.closeBrowser()

// ============================================================================
// TEST CASE 4: PETUGAS LAPANGAN - INPUT PENDATAAN UMKM BARU
// ============================================================================
WebUI.comment("--- TC4: PETUGAS LAPANGAN INPUT UMKM ---")
WebUI.openBrowser('')
WebUI.navigateToUrl(baseUrl + "/login")
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'petugas@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))

// Navigasi Form Input UMKM Baru
WebUI.navigateToUrl(baseUrl + "/operator/umkm/create")
WebUI.verifyTextPresent('Input UMKM Baru', false)

// Isi Form Pendataan
String uniqueUsahaName = "UMKM Test Auto " + System.currentTimeMillis()
WebUI.setText(findTestObject('Object Repository/Petugas/input_nama_usaha'), uniqueUsahaName)
WebUI.selectOptionByLabel(findTestObject('Object Repository/Petugas/select_sektor'), 'Kuliner', false)
WebUI.setText(findTestObject('Object Repository/Petugas/input_nik_pemilik'), '3273012345670001')
WebUI.setText(findTestObject('Object Repository/Petugas/input_nama_pemilik'), 'Budi Santoso')
WebUI.setText(findTestObject('Object Repository/Petugas/input_telp_usaha'), '081234567890')
WebUI.setText(findTestObject('Object Repository/Petugas/textarea_alamat'), 'Jl. Mandalajati No. 45')

WebUI.comment("Form pendataan berhasil diisi: " + uniqueUsahaName)
WebUI.closeBrowser()

// ============================================================================
// TEST CASE 5: PELAKU UMKM - KATALOG PRODUK & PENGAJUAN BANTUAN
// ============================================================================
WebUI.comment("--- TC5: PELAKU UMKM KATALOG & PENGAJUAN ---")
WebUI.openBrowser('')
WebUI.navigateToUrl(baseUrl + "/login")
WebUI.setText(findTestObject('Object Repository/Page_Login/input_email'), 'pelaku@mandalaloka.com')
WebUI.setText(findTestObject('Object Repository/Page_Login/input_password'), 'password123')
WebUI.click(findTestObject('Object Repository/Page_Login/button_Submit'))

// Navigasi Profil Saya
WebUI.navigateToUrl(baseUrl + "/pelaku/profil")
WebUI.verifyTextPresent('Profil Saya', false)

// Navigasi Katalog Produk
WebUI.navigateToUrl(baseUrl + "/pelaku/produk")
WebUI.verifyTextPresent('Katalog Produk', false)

WebUI.comment("All Test Cases executed successfully!")
WebUI.closeBrowser()
