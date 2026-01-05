@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-page-root">
    <div class="container vh-100 d-flex align-items-center justify-content-end position-relative login-right-padding">
    <div class="ecg-area d-none d-md-block" aria-hidden="true">
        <svg viewBox="0 0 2000 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Primary ECG line (sharper peaks) -->
            <polyline id="ecg-primary" class="ecg-line ecg-layer1" points="0,100 40,100 60,70 76,140 92,100 180,100 220,100 240,80 256,150 272,100 380,100 420,100 440,70 456,150 472,100 580,100 620,100 640,85 656,150 672,100 780,100 820,100 840,60 856,150 872,100 980,100 1020,100 1040,82 1056,148 1072,100 1180,100 1220,100 1240,68 1256,152 1272,100 1380,100 1420,100 1440,86 1456,150 1472,100 1580,100 1620,100 1640,72 1656,148 1672,100 1780,100 1820,100 1840,80 1856,150 1872,100 2000,100" />
            <!-- Duplicate copy shifted right for seamless looping -->
            <polyline class="ecg-line ecg-layer1" transform="translate(2000,0)" points="0,100 40,100 60,70 76,140 92,100 180,100 220,100 240,80 256,150 272,100 380,100 420,100 440,70 456,150 472,100 580,100 620,100 640,85 656,150 672,100 780,100 820,100 840,60 856,150 872,100 980,100 1020,100 1040,82 1056,148 1072,100 1180,100 1220,100 1240,68 1256,152 1272,100 1380,100 1420,100 1440,86 1456,150 1472,100 1580,100 1620,100 1640,72 1656,148 1672,100 1780,100 1820,100 1840,80 1856,150 1872,100 2000,100" />
            <!-- Secondary ECG line (softer, slower) -->
            <polyline class="ecg-line ecg-layer2" points="0,108 60,108 90,98 110,124 130,108 260,108 300,108 340,100 380,120 420,108 560,108 600,108 640,96 680,120 720,108 860,108 900,108 940,102 980,122 1020,108 1160,108 1200,108 1240,94 1280,122 1320,108 1460,108 1500,108 1540,100 1580,118 1620,108 1760,108 1800,108 1840,96 1880,118 1920,108 2000,108" />
            <!-- Duplicate copy shifted right for seamless looping -->
            <polyline class="ecg-line ecg-layer2" transform="translate(2000,0)" points="0,108 60,108 90,98 110,124 130,108 260,108 300,108 340,100 380,120 420,108 560,108 600,108 640,96 680,120 720,108 860,108 900,108 940,102 980,122 1020,108 1160,108 1200,108 1240,94 1280,122 1320,108 1460,108 1500,108 1540,100 1580,118 1620,108 1760,108 1800,108 1840,96 1880,118 1920,108 2000,108" />
        </svg>
    </div>


    <div class="card shadow-sm" style="max-width: 1000px; min-height: 100px;">
        <div class="row g-0">

            <!-- IMAGE -->  
            <div class="col-md-6 d-none d-md-block">
                <img 
                    src="https://placehold.co/1200x900"
                    class="img-fluid w-100 h-100 rounded-start"

                >
            </div>

            <!-- FORM -->
            <div class="col-md-6">
                <div class="card-body p-5">
                    <h2 style="font-family: 'Poppins', sans-serif;">NARS</h2>
                    <h6 class="text-muted mb-4">Welcome Back</h6>

                    <form>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control mb-2">
                            <a href="#" class="small">Forgot Password?</a>
                        </div>

                        <button class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>






    @push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    var poly = document.getElementById('ecg-primary');
    if (!poly) return;

    // store original points
    var base = poly.getAttribute('points').trim();
    var basePoints = base.split(/\s+/).map(function (p) {
        var parts = p.split(',');
        return {x: parseFloat(parts[0]), y: parseFloat(parts[1])};
    });

    // choose indexes that correspond to peaks (these indices chosen from the markup)
    var peakIndexes = [];
    for (var i = 0; i < basePoints.length; i++) {
        // pick points that have y significantly different from 100 baseline (approx peaks)
        if (Math.abs(basePoints[i].y - 100) > 12) peakIndexes.push(i);
    }

    function randomBeatVariation() {
        // clone base
        var pts = basePoints.map(function (p) { return {x: p.x, y: p.y}; });

        // For each peak, apply a subtle random offset to simulate natural variability
        peakIndexes.forEach(function (idx) {
            var spike = pts[idx];
            if (!spike) return;
            var offset = (Math.random() * 18) - 9; // -9 to +9 px
            spike.y = Math.max(48, Math.min(170, spike.y + offset));
            // also slightly nudge surrounding points for smoother shape
            if (pts[idx-1]) pts[idx-1].y = pts[idx-1].y + (offset * 0.25);
            if (pts[idx+1]) pts[idx+1].y = pts[idx+1].y + (offset * 0.25);
        });

        // convert back to points string
        var s = pts.map(function(p){ return Math.round(p.x)+','+Math.round(p.y); }).join(' ');
        poly.setAttribute('points', s);
    }

    // change beat pattern every few seconds (staggered so it's not perfectly periodic)
    setInterval(function () {
        randomBeatVariation();
    }, 1300 + Math.round(Math.random()*900));

    // also occasionally do a slightly larger variation
    setInterval(function () {
        // larger spike variation
        var pts = basePoints.map(function (p) { return {x: p.x, y: p.y}; });
        peakIndexes.forEach(function (idx) {
            var spike = pts[idx];
            if (!spike) return;
            var offset = (Math.random() * 28) - 14; // -14 to +14 px
            spike.y = Math.max(48, Math.min(170, spike.y + offset));
        });
        var s = pts.map(function(p){ return Math.round(p.x)+','+Math.round(p.y); }).join(' ');
        poly.setAttribute('points', s);
    }, 6000 + Math.round(Math.random()*3000));

    // restore base occasionally to avoid drift
    setInterval(function () { poly.setAttribute('points', base); }, 18000);
});
</script>
@endpush
</div>
@endsection
