@php
    $flyerActive = \App\Models\Setting::get('flyer_popup_active', false);
    $flyerImage = \App\Models\Setting::get('flyer_popup_image', '');
    $flyerLink = \App\Models\Setting::get('flyer_popup_link', '');
    $flyerTarget = \App\Models\Setting::get('flyer_popup_target', 'both');

    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $isWelcome = ($currentRoute === 'welcome');
    $isDashboard = str_starts_with($currentRoute, 'pelaku.');

    $shouldShow = false;
    if ($flyerActive && $flyerImage) {
        if ($flyerTarget === 'both') {
            $shouldShow = ($isWelcome || $isDashboard);
        } elseif ($flyerTarget === 'welcome') {
            $shouldShow = $isWelcome;
        } elseif ($flyerTarget === 'dashboard') {
            $shouldShow = $isDashboard;
        }
    }

    $flyerHash = md5($flyerImage);
@endphp

@if ($shouldShow)
    <style>
        #program-flyer-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            padding: 16px;
            box-sizing: border-box;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #program-flyer-modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        #program-flyer-container {
            position: relative;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-sizing: border-box;
        }
        #program-flyer-modal.active #program-flyer-container {
            transform: scale(1);
        }
        .flyer-close-btn {
            position: absolute;
            top: -12px;
            right: -12px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            outline: none;
            z-index: 100001;
            transition: all 0.2s ease;
            padding: 0;
        }
        @media (min-width: 768px) {
            .flyer-close-btn {
                top: -16px;
                right: -16px;
                width: 40px;
                height: 40px;
            }
        }
        .flyer-close-btn:hover {
            background-color: #f8fafc;
            transform: scale(1.05);
        }
        .flyer-close-btn:active {
            transform: scale(0.95);
        }
        .flyer-close-icon {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
        }
        @media (min-width: 768px) {
            .flyer-close-icon {
                width: 24px;
                height: 24px;
            }
        }
        .flyer-image-wrapper {
            background-color: transparent;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 100%;
            position: relative;
        }
        .flyer-img-el {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 16px;
            display: block;
            transition: filter 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        @media (min-width: 768px) {
            .flyer-img-el {
                max-height: 85vh;
                max-width: 768px;
            }
        }
        .flyer-link-overlay {
            display: block;
            text-decoration: none;
            cursor: pointer;
            outline: none;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .flyer-link-overlay:hover .flyer-img-el {
            filter: brightness(0.95);
        }
        .flyer-badge-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
            border-radius: 16px;
        }
        .flyer-link-overlay:hover .flyer-badge-container {
            background-color: rgba(0, 0, 0, 0.2);
        }
        .flyer-badge {
            opacity: 0;
            transform: scale(0.9);
            background-color: rgba(79, 70, 229, 0.9);
            color: #ffffff;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        .flyer-link-overlay:hover .flyer-badge {
            opacity: 1;
            transform: scale(1);
        }
        .flyer-badge-icon {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
        }
    </style>

    <div id="program-flyer-modal">
        <div id="program-flyer-container">
            
            <button onclick="closeProgramFlyer()" class="flyer-close-btn" aria-label="Tutup">
                <svg class="flyer-close-icon" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flyer-image-wrapper">
                @if ($flyerLink)
                    <a href="{{ $flyerLink }}" target="_blank" onclick="closeProgramFlyer()" class="flyer-link-overlay">
                        <img src="{{ asset('storage/' . $flyerImage) }}" alt="Flyer Program Bantuan" class="flyer-img-el">
                        <div class="flyer-badge-container">
                            <span class="flyer-badge">
                                <svg class="flyer-badge-icon" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span>Lihat Informasi Detail</span>
                            </span>
                        </div>
                    </a>
                @else
                    <img src="{{ asset('storage/' . $flyerImage) }}" alt="Flyer Program Bantuan" class="flyer-img-el">
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('program-flyer-modal');
            if (modal) {
                if (!localStorage.getItem('flyer_closed_{{ $flyerHash }}')) {
                    document.body.style.overflow = 'hidden';
                    modal.classList.add('active');
                }

                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeProgramFlyer();
                    }
                });
            }
        });

        function closeProgramFlyer() {
            const modal = document.getElementById('program-flyer-modal');
            if (modal) {
                document.body.style.overflow = '';
                modal.classList.remove('active');
                localStorage.setItem('flyer_closed_{{ $flyerHash }}', 'true');
            }
        }
    </script>
@endif
