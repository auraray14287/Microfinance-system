<!-- <a href="">
    <img src="{{asset('logo.jpg')}}" style="width:90px;height:90px;border-radius:100%" alt="Sign In"/>
</a> -->
<a href="{{ url('/') }}">
    @php
        $logoPath = null;
        if (file_exists(public_path('companyLogo.png')))             { $logoPath = asset('companyLogo.png'); }
        elseif (file_exists(public_path('images/rafikibora-logo.png'))) { $logoPath = asset('images/rafikibora-logo.png'); }
        elseif (file_exists(public_path('logo.jpg')))                { $logoPath = asset('logo.jpg'); }
        elseif (file_exists(public_path('logo.png')))                { $logoPath = asset('logo.png'); }
    @endphp
    @if($logoPath)
        <img src="{{ $logoPath }}"
             style="width:200px; height:100px; object-fit:contain; display:block; margin:0 auto;"
             alt="Rafiki Bora Microfinance" />
    @else
        <div style="width:100px;height:100px;border:2px solid #8b6914;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#2d5016;font-size:22px;margin:0 auto;">
            RBM
        </div>
    @endif
</a>