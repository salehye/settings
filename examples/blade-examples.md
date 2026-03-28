# Blade Examples

## 1. Access Settings in Any Blade View

```blade
{{-- Automatically available via View Composer --}}
<header>
    <h1>{{ $settings['site_name'] ?? 'My Website' }}</h1>
    <p>{{ $settings['site_description'] ?? '' }}</p>
</header>

<footer>
    <p>Email: {{ $settings['contact_email'] ?? 'info@example.com' }}</p>
    <p>Phone: {{ $settings['contact_phone'] ?? '' }}</p>
</footer>
```

## 2. Using Blade Directives

```blade
{{-- Simple output --}}
<title>@settings('seo_title')</title>
<meta name="description" content="@setting('seo_description')">

{{-- Conditional --}}
@ifSettings('maintenance_mode')
    <div class="alert alert-warning">
        Site is under maintenance
    </div>
@endIfSettings

{{-- Social Media Links --}}
@ifSettings('social_twitter')
    <a href="@settings('social_twitter')" target="_blank">Twitter</a>
@endIfSettings

@ifSettings('social_facebook')
    <a href="@settings('social_facebook')" target="_blank">Facebook</a>
@endIfSettings

{{-- JSON Settings --}}
<script>
    const seoKeywords = @jsonSettings('seo_keywords');
    console.log(seoKeywords);
</script>
```

## 3. Using Blade Component

```blade
{{-- In your layout --}}
<title><x-setting key="seo_title" :default="'My Website'" /></title>

{{-- In your content --}}
<p>Contact us: <x-setting key="contact_email" /></p>
```

## 4. Complete Layout Example

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO Settings --}}
    <title>@settings('seo_title') - @settings('site_name')</title>
    <meta name="description" content="@settings('seo_description')">
    <meta name="keywords" content="@jsonSettings('seo_keywords')">
    
    {{-- Social Media Meta Tags --}}
    <meta property="og:title" content="@settings('seo_title')">
    <meta property="og:description" content="@settings('seo_description')">
    <meta property="og:site_name" content="@settings('site_name')">
</head>
<body>
    {{-- Header --}}
    <header>
        <nav>
            <h1>@settings('site_name')</h1>
            
            {{-- Social Links --}}
            <div class="social-links">
                @ifSettings('social_twitter')
                    <a href="@settings('social_twitter')" target="_blank">
                        <x-twitter-icon />
                    </a>
                @endIfSettings
                
                @ifSettings('social_facebook')
                    <a href="@settings('social_facebook')" target="_blank">
                        <x-facebook-icon />
                    </a>
                @endIfSettings
                
                @ifSettings('social_instagram')
                    <a href="@settings('social_instagram')" target="_blank">
                        <x-instagram-icon />
                    </a>
                @endIfSettings
                
                @ifSettings('social_linkedin')
                    <a href="@settings('social_linkedin')" target="_blank">
                        <x-linkedin-icon />
                    </a>
                @endIfSettings
            </div>
        </nav>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        <div class="contact-info">
            @ifSettings('contact_email')
                <p>Email: @settings('contact_email')</p>
            @endIfSettings
            
            @ifSettings('contact_phone')
                <p>Phone: @settings('contact_phone')</p>
            @endIfSettings
            
            @ifSettings('contact_address')
                <p>Address: @settings('contact_address')</p>
            @endIfSettings
        </div>
        
        <p>&copy; {{ date('Y') }} @settings('site_name'). All rights reserved.</p>
    </footer>

    {{-- Maintenance Mode Alert --}}
    @ifSettings('maintenance_mode')
        <div class="maintenance-alert">
            <p>⚠️ Website is currently under maintenance</p>
        </div>
    @endIfSettings
</body>
</html>
```

## 5. Settings Admin Page

```blade
{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Settings</h1>
    
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        {{-- General Settings --}}
        <section>
            <h2>General</h2>
            
            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" 
                       name="settings[site_name]" 
                       id="site_name" 
                       value="{{ $settings['site_name'] ?? old('settings.site_name') }}"
                       class="form-control">
            </div>
            
            <div class="form-group">
                <label for="site_description">Site Description</label>
                <textarea name="settings[site_description]" 
                          id="site_description"
                          class="form-control">{{ $settings['site_description'] ?? old('settings.site_description') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select name="settings[timezone]" id="timezone" class="form-control">
                    @foreach(timezone_identifiers_list() as $timezone)
                        <option value="{{ $timezone }}" 
                                {{ ($settings['timezone'] ?? 'UTC') === $timezone ? 'selected' : '' }}>
                            {{ $timezone }}
                        </option>
                    @endforeach
                </select>
            </div>
        </section>
        
        {{-- SEO Settings --}}
        <section>
            <h2>SEO</h2>
            
            <div class="form-group">
                <label for="seo_title">Page Title</label>
                <input type="text" 
                       name="settings[seo_title]" 
                       id="seo_title" 
                       value="{{ $settings['seo_title'] ?? old('settings.seo_title') }}"
                       class="form-control">
            </div>
            
            <div class="form-group">
                <label for="seo_description">Meta Description</label>
                <textarea name="settings[seo_description]" 
                          id="seo_description"
                          class="form-control">{{ $settings['seo_description'] ?? old('settings.seo_description') }}</textarea>
            </div>
        </section>
        
        {{-- Social Media --}}
        <section>
            <h2>Social Media</h2>
            
            <div class="form-group">
                <label for="social_twitter">Twitter URL</label>
                <input type="url" 
                       name="settings[social_twitter]" 
                       id="social_twitter" 
                       value="{{ $settings['social_twitter'] ?? old('settings.social_twitter') }}"
                       class="form-control">
            </div>
            
            <div class="form-group">
                <label for="social_facebook">Facebook URL</label>
                <input type="url" 
                       name="settings[social_facebook]" 
                       id="social_facebook" 
                       value="{{ $settings['social_facebook'] ?? old('settings.social_facebook') }}"
                       class="form-control">
            </div>
        </section>
        
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
```

## 6. PHP + Blade Mixed

```blade
{{-- In Controller --}}
@php
    $siteName = settings('site_name');
    $generalSettings = settings_group('general');
@endphp

<h1>{{ $siteName }}</h1>

{{-- Loop through group --}}
@foreach($generalSettings as $key => $value)
    <p>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</p>
@endforeach
```

## 7. Helper Functions in Blade

```blade
{{-- All helpers work in Blade --}}
<p>{{ settings('site_name') }}</p>
<p>{{ setting('site_name', 'Default') }}</p>

{{-- In PHP blocks --}}
@php
    $settings = settings_all();
    $public = settings_public();
    $general = settings_group('general');
@endphp
```
