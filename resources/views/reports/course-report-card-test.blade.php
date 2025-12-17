<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=794, initial-scale=1.0">
    <title>Course Report Card</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
        * {
            font-family: 'Montserrat', sans-serif;
        }
        html, body {
            width: 794px;
            min-width: 794px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-300 min-h-screen flex justify-center items-start py-8 print:p-0 print:bg-white">

    <!-- A4 Container: 794px x 1123px (A4 at 96dpi) -->
    <div class="w-[794px] h-[1123px] shadow-2xl relative overflow-hidden print:shadow-none flex flex-col" style="min-width: 794px; min-height: 1123px;">
        
        <!-- Background: Left 1/3 is light blue, Right 2/3 is white -->
        <div class="absolute inset-0 flex">
            <div class="w-[280px] bg-[#ecf8ff]"></div>
            <div class="flex-1 bg-white"></div>
        </div>
        
        <!-- Vertical Dashed Separator Line between blue and white -->
        <div class="absolute top-[180px] bottom-6 left-[280px] w-0 border-l-2 border-dashed border-[#B0BEC5] z-10"></div>
        
        <!-- Left Blue Border/Decoration (notebook lines) -->
        <div class="absolute left-0 top-0 bottom-0 w-3 bg-[#D4E8F2]"></div>
        <div class="absolute left-3 top-0 bottom-0 w-[2px] bg-[#90CAF9] opacity-50"></div>
        <div class="absolute left-5 top-0 bottom-0 w-[1px] bg-[#90CAF9] opacity-30"></div>
        
        <!-- Top Decorative Pink Elements -->
        <div class="absolute top-6 z-20 left-28 flex gap-1">
            <div class="w-10 h-9 bg-[#fd438d]"></div>
            <div class="w-10 h-9 bg-[#fd438d]"></div>
        </div>

        <!-- Header Section -->
        <div class="px-20 pt-8 pb-4 relative z-10">
            <div class="border-[8px] border-[#1798c9] rounded-[1.5rem] py-4 px-6 text-center relative bg-white">
                <!-- Logo Area -->
                <div class="flex justify-center items-center gap-1 mb-1">
                    <!-- Cotha Logo -->
                    <div class="flex items-center">
                        <img class="h-14" src="{{ asset('LogoCotha.png') }}">
                    </div>
                </div>
                
                <h1 class="text-[#1798c9] text-3xl font-medium tracking-wider mb-1">COURSE REPORT CARD</h1>
                <h2 class="text-[#1798c9] text-xs font-bold tracking-[0.15em] mb-3">COMPUTATIONAL THINKING ACADEMY</h2>
                
                <div class="flex justify-center items-center gap-6 text-[#1798c9] text-xs font-medium">
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        www.cotha.id
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Indonesia
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 pl-7 pr-8 pb-6 gap-0 relative z-10">
            
            <!-- Left Column -->
            <div class="w-[240px] flex flex-col items-center pt-2 shrink-0">
                <!-- Student Photo with graduation cap -->
                <div class="w-28 h-28 bg-[#4FC3F7] rounded-full flex items-center justify-center mb-4 text-white overflow-hidden relative">
                    <!-- Graduation cap icon -->
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                    </svg>
                </div>

                <!-- Student Name -->
                <h3 class="text-center font-bold text-base leading-tight mb-4 text-gray-800 tracking-wide">
                    {{ strtoupper($user->name ?? 'Jonathan Riley') }}
                </h3>

                <!-- Final Score -->
                <div class="w-4/5 bg-[#B3E5FC] text-center py-2 rounded-full mb-1">
                    <span class="font-bold text-base text-gray-800">{{ $finalScore ?? '90 - Excellent' }}</span>
                </div>
                <p class="text-black text-xs mb-5">Final Score</p>

                <!-- Metrics -->
                <div class=" space-y-4 mb-8">
                    <div class="text-center  pb-1">
                        <div class="font-bold text-base text-gray-800">{{ $projectAchievement ?? 'Advanced' }}</div>
                        <hr class="bg-gray-600 my-1 h-[2px]">
                        <div class="text-gray-500 text-xs">Project Achievement</div>
                    </div>
                    <div class="text-center ">
                        <div class="font-bold text-base text-gray-800">{{ $learningEfficiency ?? 'Very High' }}</div>
                        <hr class="bg-gray-600 my-1 h-[2px]">
                        <div class="text-gray-500 text-xs">Learning Efficiency</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-base text-gray-800">{{ $learningEngagement ?? 'Active' }}</div>
                        <hr class="bg-gray-600 my-1 h-[2px]">
                        <div class="text-gray-500 text-xs">Learning Engagement</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-base text-gray-800">{{ $attendanceConsistency ?? 'Good' }}</div>
                        <hr class="bg-gray-600 my-1 h-[2px]">
                        <div class="text-gray-500 text-xs">Attendance Consistency</div>
                    </div>
                </div>

                <!-- Student Projects -->
                <div class="w-full mb-6">
                    <h4 class="font-bold text-xs text-center mb-3 uppercase tracking-wide text-gray-800">STUDENT PROJECTS</h4>
                    <div class="flex gap-3 justify-center">
                        <!-- QR Code -->
                        <div class="w-16 h-16 bg-white border border-gray-300 shrink-0 p-1">
                            {!! QrCode::size(56)->generate($showcaseUrl ?? url('/')) !!}
                        </div>
                        <div class="text-[12px] text-gray-500 space-y-0.5 leading-relaxed">
                            @php
                                $defaultProjects = ['Starfish Jump', 'Ghost Hunter', 'Dino Jump', 'Catch the Fruit', 'Maze Runner', 'Spacewar'];
                                $projects = $projectsList ?? $defaultProjects;
                            @endphp
                            @if(count($projects) > 0)
                                @foreach($projects as $project)
                                    <div>{{ $project }}</div>
                                @endforeach
                            @else
                                <div>No projects completed yet</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Signature -->
                <div class="">
                    <p class="text-xs  text-gray-500 mb-1">Surabaya, {{ $date ?? '10 Desember 2025' }}</p>
                    <div class="  text-center flex justify-center">
                        <!-- Signature -->
                        <img class="h-20"  src="{{ asset('laura-signature.png') }}">
                    </div>
                    <p class="font-bold text-xs ">L.M. Tjahjono</p>
                    <p class="text-[10px] ">Academic Director</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex-1 flex flex-col gap-4 pt-0 pl-8">
                
                <!-- Course -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-gray-500">
                        <span class="text-xs">📖</span>
                        <span class="text-xs">Course</span>
                    </div>
                    <div class="bg-[#ecf8ff] py-2.5 px-4 rounded-xl w-full">
                        <h3 class="font-bold text-lg text-center text-gray-800">{{ strtoupper($courseName ?? '2D & 3D Basic Game Coding') }}</h3>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-gray-500">
                        <span class="text-xs">📋</span>
                        <span class="text-xs">Description</span>
                    </div>
                    <div class="bg-[#ecf8ff] p-4 rounded-2xl w-full">
                        <p class="text-gray-600 text-md leading-relaxed text-center">
                            Students strengthen their computational thinking skills by designing and programming simple games using block-based coding. This course introduces fundamental concepts such as sequencing, loops, events, and logical decision-making.
                        </p>
                    </div>
                </div>

                <!-- Learning Concepts -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-gray-500">
                        <span class="text-xs">🧪</span>
                        <span class="text-xs">Learning Concepts</span>
                    </div>
                    <div class="bg-[#ecf8ff] p-4 rounded-2xl w-full">
                        <p class="text-gray-600 text-md leading-relaxed text-center">
                            Algorithm design, Sequential programming, Position & coordinates, Direction & movement, Conditional statements, Loops repetition, Event handling, Variables, Value passing, Function development
                        </p>
                    </div>
                </div>

                <!-- Duration & Teacher -->
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-gray-500">
                        <span class="text-xs">👨‍🏫</span>
                        <span class="text-xs">Duration & Teacher</span>
                    </div>
                    <div class="bg-[#ecf8ff] py-3 px-4 rounded-xl w-full text-center">
                        <p class="text-gray-600 text-md">August - December 2025 (20 Sessions)</p>
                        <p class="text-gray-600 text-md">Mr. Haning Galih</p>
                    </div>
                </div>

                <!-- Learning Progress Summary -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1.5 text-gray-500">
                        <span class="text-xs">📊</span>
                        <span class="text-xs">Learning Progress Summary</span>
                    </div>
                    <div class="bg-[#ecf8ff] p-4  rounded-2xl w-full h-[90%]">
                        <p class="text-gray-600 text-[15px] text-center leading-relaxed ">
                            The student demonstrates a learning pace that is {{ $learningEfficiency ?? 'Very High' }}, evident from their ability to complete projects at an {{ $projectAchievement ?? 'Advanced' }} level appropriate to their stage. Throughout the learning process, the student appears {{ $learningEngagement ?? 'Active' }}, making the learning experience more vibrant. Their {{ $attendanceConsistency ?? 'Good' }} attendance helps maintain the flow of learning. Overall, the student shows {{ $finalScore ?? '90 - Excellent' }} performance and has great potential to develop further through consistent practice and proper guidance.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
