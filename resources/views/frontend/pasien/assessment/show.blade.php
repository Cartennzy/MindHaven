@extends('frontend.layouts.app')

@section('title', 'Kuesioner Tes ' . $instrumen->nama_tes . ' - MindHaven')
@section('page_title', 'Lembar Asesmen Mandiri')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="overflow-hidden rounded-[36px] bg-white shadow-[0_24px_80px_rgba(15,23,42,0.06)] border border-slate-100">
        
        {{-- Header Status Form --}}
        <div class="bg-slate-900 p-6 text-white flex items-center justify-between md:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('pasien.self-assessment.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white transition hover:bg-white/20">
                    <i class="fas fa-chevron-left text-sm"></i>
                </a>
                <div>
                    <h2 class="text-lg font-black">Kuesioner {{ $instrumen->nama_tes }}</h2>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">MindHaven Psychometric Test</p>
                </div>
            </div>
            <div class="text-right">
                <span id="wizardProgressText" class="text-sm font-black text-[#41AD01]">1 / {{ $instrumen->pertanyaans->count() }}</span>
            </div>
        </div>

        {{-- Progress Bar Indicator --}}
        <div class="h-1.5 w-full bg-slate-100">
            <div id="wizardProgressBar" class="h-full bg-gradient-to-r from-[#01588E] to-[#41AD01] transition-all duration-300" style="width: 0%;"></div>
        </div>

        <form action="{{ route('pasien.self-assessment.store', $instrumen->slug) }}" method="POST" id="wizardAssessmentForm" class="p-6 md:p-8">
            @csrf
            
            {{-- Instruction Card --}}
            <div class="mb-8 rounded-2xl bg-blue-50/60 p-4 border border-blue-100 flex items-start gap-3">
                <i class="fas fa-circle-info text-base text-[#01588E] mt-0.5"></i>
                <p class="text-xs font-semibold leading-5 text-slate-600">
                    Pilihlah salah satu opsi jawaban yang paling menggambarkan kondisi atau frekuensi apa yang Anda rasakan selama <span class="text-[#01588E] font-black">1 bulan terakhir ini</span>.
                </p>
            </div>

            {{-- Container Wizard Questions --}}
            <div class="space-y-6">
                @foreach($instrumen->pertanyaans as $index => $pertanyaan)
                    <div class="wizard-step-container hidden" data-step="{{ $index + 1 }}">
                        
                        {{-- Label Pertanyaan --}}
                        <div class="rounded-3xl bg-slate-50 p-6 border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Pertanyaan {{ $index + 1 }}</span>
                            <h3 class="text-lg md:text-xl font-black text-slate-800 leading-relaxed">
                                {{ $pertanyaan->teks_pertanyaan }}
                            </h3>
                        </div>

                        {{-- Pilihan Opsi Radio Poin --}}
                        <div class="mt-6 space-y-3">
                            @foreach($pertanyaan->pilihan_opsi as $opsiIndex => $opsi)
                                <label class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 cursor-pointer transition-all duration-200 hover:border-[#01588E]/40 hover:bg-slate-50/50">
                                    <input type="radio" 
                                           name="jawaban[{{ $pertanyaan->id_pertanyaan }}]" 
                                           value="{{ $opsi['poin'] }}" 
                                           class="h-5 w-5 text-[#01588E] focus:ring-[#01588E]/20 border-slate-300"
                                           required
                                           data-question="{{ $index + 1 }}">
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-700">{{ $opsi['teks'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Wizard Action Navigation Bar --}}
            <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-6">
                <button type="button" id="prevBtn" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-200 invisible">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Sebelumnya
                </button>
                
                <button type="button" id="nextBtn" class="inline-flex items-center gap-2 rounded-xl bg-[#01588E] px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#01446e] disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>

                {{-- PERBAIKAN: Menghilangkan class inline-flex agar tidak konflik dengan hidden di awal --}}
                <button type="submit" id="submitBtn" class="items-center justify-center gap-2 rounded-xl bg-[#41AD01] px-6 py-3 text-sm font-black text-white shadow-md transition hover:bg-[#329000] hidden">
                    <i class="fas fa-paper-plane text-xs"></i>
                    Lihat Hasil Analisis
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = {{ $instrumen->pertanyaans->count() }};
        const steps = document.querySelectorAll('.wizard-step-container');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressText = document.getElementById('wizardProgressText');
        const progressBar = document.getElementById('wizardProgressBar');

        function updateWizardUI() {
            steps.forEach(step => {
                step.classList.add('hidden');
                if (parseInt(step.dataset.step) === currentStep) {
                    step.classList.remove('hidden');
                }
            });

            // Update Progress Bar & Text
            progressText.textContent = `${currentStep} / ${totalSteps}`;
            progressBar.style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;

            // Atur Visibilitas Tombol Navigasi
            if (currentStep === 1) {
                prevBtn.classList.add('invisible');
            } else {
                prevBtn.classList.remove('invisible');
            }

            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                // Ketika ditampilkan, gunakan gabungan flex melalui JS untuk mempertahankan style layout
                submitBtn.classList.remove('hidden');
                submitBtn.classList.add('flex');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                submitBtn.classList.remove('flex');
            }
            
            checkStepValidation();
        }

        function checkStepValidation() {
            const activeStepRadio = document.querySelector(`.wizard-step-container[data-step="${currentStep}"] input[type="radio"]:checked`);
            nextBtn.disabled = !activeStepRadio;
        }

        // Auto-next saat user memilih opsi jawaban
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                checkStepValidation();
                setTimeout(() => {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateWizardUI();
                    }
                }, 200);
            });
        });

        nextBtn.addEventListener('click', function () {
            if (currentStep < totalSteps) {
                currentStep++;
                updateWizardUI();
            }
        });

        prevBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                updateWizardUI();
            }
        });

        updateWizardUI();
    });
</script>
@endsection