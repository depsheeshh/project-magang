@extends('layouts.form-layout')

@section('title', 'Check-in Pending')

@section('content')
    <style>
        /* ==== GLOBAL ==== */
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 20% 20%, #0d1117, #0b1220 60%, #0a0e1a);
            color: #e2e8f0;
            overflow-y: auto;
        }

        /* ==== BACKGROUND ANIMATION ==== */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 40%, rgba(80, 130, 255, 0.1), transparent 60%);
            animation: moveGlow 8s infinite alternate ease-in-out;
            z-index: 0;
        }

        @keyframes moveGlow {
            from {
                transform: translate(0, 0);
                opacity: 0.6;
            }

            to {
                transform: translate(50px, 40px);
                opacity: 0.9;
            }
        }

        /* ==== PAGE SECTION ==== */
        .page-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            padding: 50px 15px 80px 15px;
        }

        /* ==== CARD ==== */
        .card {
            border-radius: 24px;
            background: rgba(20, 25, 40, 0.85);
            box-shadow: 0 0 30px rgba(100, 150, 255, 0.25),
                inset 0 0 20px rgba(120, 150, 255, 0.08);
            border: 1px solid rgba(130, 150, 255, 0.2);
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 50px rgba(120, 150, 255, 0.4);
        }

        /* ==== ICON ==== */
        .pending-icon {
            color: #facc15;
            /* kuning */
            text-shadow: 0 0 15px rgba(250, 204, 21, 0.5);
            animation: popIn 0.6s ease-out forwards;
        }

        @keyframes popIn {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ==== TEXT ==== */
        h2 {
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #60a5fa, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lead {
            font-size: 15px;
            color: #cbd5e1;
        }

        /* ==== BUTTON ==== */
        .btn-primary {
            background: linear-gradient(135deg, #5c6cff, #00d4ff);
            border: none;
            padding: 13px 32px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #fff;
            box-shadow: 0 0 20px rgba(90, 140, 255, 0.3);
            transition: 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(120, 160, 255, 0.6);
        }

        /* ==== FLOATING PARTICLES DECOR ==== */
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(100, 150, 255, 0.15), transparent 70%);
            animation: floaty 8s infinite ease-in-out;
        }

        .floating-circle.one {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 15%;
        }

        .floating-circle.two {
            width: 300px;
            height: 300px;
            bottom: 5%;
            right: 10%;
            animation-delay: 2s;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.1);
            }
        }

        /* ==== RESEND BUTTON ==== */
        .btn-outline-resend {
            background: transparent;
            border: 2px solid #60a5fa;
            color: #60a5fa;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: 0.3s ease;
        }

        .btn-outline-resend:hover {
            background: rgba(96, 165, 250, 0.15);
            color: #93c5fd;
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(96, 165, 250, 0.3);
        }

        /* ==== SPAM NOTICE ==== */
        .spam-notice {
            background: rgba(250, 204, 21, 0.1);
            border: 1px solid rgba(250, 204, 21, 0.3);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            color: #fef3c7;
        }

        /* ==== ALERT BOXES ==== */
        .alert-box {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            text-align: left;
        }

        .alert-success-box {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
        }

        .alert-error-box {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }
    </style>

    <div class="particles"></div>
    <div class="floating-circle one"></div>
    <div class="floating-circle two"></div>

    <section class="page-section text-center">
        <div class="container">
            <div class="card border-0 mx-auto" style="max-width: 620px;" data-aos="zoom-in">
                <div class="card-body py-5 px-4">

                    {{-- Alert success/error dari resend --}}
                    @if (session('success'))
                        <div class="alert-box alert-success-box mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert-box alert-error-box mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <i class="fas fa-envelope fa-5x pending-icon"></i>
                    </div>
                    <h2 class="mb-3">Check-in Pending</h2>
                    <p class="lead mb-2">
                        Check-in Anda untuk rapat <strong>{{ request('rapat_name') ?? 'Rapat' }}</strong> telah
                        disubmit.<br>
                        Silakan cek email <strong>{{ session('email') ?? (auth()->user()->email ?? '-') }}</strong> untuk
                        verifikasi kehadiran.<br>
                        Link verifikasi hanya berlaku sekali.
                    </p>

                    {{-- Peringatan spam --}}
                    <div class="spam-notice mb-4">
                        <i class="fas fa-exclamation-triangle me-2" style="color:#facc15;"></i>
                        <span>Jika email tidak masuk, cek folder <strong>Spam / Junk</strong> di inbox Anda.</span>
                    </div>

                    {{-- Tombol Kirim Ulang Email --}}
                    @if (request('rapat_id') && request('undangan_id'))
                        <form action="{{ route('tamu.rapat.checkin.resend') }}" method="POST" class="mb-3"
                            id="resendForm">
                            @csrf
                            <input type="hidden" name="rapat_id" value="{{ request('rapat_id') }}">
                            <input type="hidden" name="undangan_id" value="{{ request('undangan_id') }}">
                            <button type="submit" class="btn btn-outline-resend btn-lg me-2" id="resendBtn"
                                onclick="handleResend()">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Ulang Email Verifikasi
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- AOS Animation Library --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        function handleResend() {
            const btn = document.getElementById('resendBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
            }
        }
    </script>
@endsection
