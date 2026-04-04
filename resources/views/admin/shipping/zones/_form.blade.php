@php
$isoCountries = [
    'AF'=>'Afghanistan','AL'=>'Albania','DZ'=>'Algeria','AD'=>'Andorra','AO'=>'Angola',
    'AR'=>'Argentina','AM'=>'Armenia','AU'=>'Australia','AT'=>'Austria','AZ'=>'Azerbaijan',
    'BH'=>'Bahrain','BD'=>'Bangladesh','BY'=>'Belarus','BE'=>'Belgium','BR'=>'Brazil',
    'BG'=>'Bulgaria','CA'=>'Canada','CL'=>'Chile','CN'=>'China','CO'=>'Colombia',
    'HR'=>'Croatia','CY'=>'Cyprus','CZ'=>'Czechia','DK'=>'Denmark','EG'=>'Egypt',
    'EE'=>'Estonia','FI'=>'Finland','FR'=>'France','DE'=>'Germany','GR'=>'Greece',
    'HK'=>'Hong Kong','HU'=>'Hungary','IS'=>'Iceland','IN'=>'India','ID'=>'Indonesia',
    'IQ'=>'Iraq','IE'=>'Ireland','IL'=>'Israel','IT'=>'Italy','JP'=>'Japan',
    'JO'=>'Jordan','KZ'=>'Kazakhstan','KE'=>'Kenya','KW'=>'Kuwait','LB'=>'Lebanon',
    'LY'=>'Libya','LT'=>'Lithuania','LU'=>'Luxembourg','MY'=>'Malaysia','MX'=>'Mexico',
    'MA'=>'Morocco','NL'=>'Netherlands','NZ'=>'New Zealand','NG'=>'Nigeria','NO'=>'Norway',
    'OM'=>'Oman','PK'=>'Pakistan','PS'=>'Palestine','PE'=>'Peru','PH'=>'Philippines',
    'PL'=>'Poland','PT'=>'Portugal','QA'=>'Qatar','RO'=>'Romania','RU'=>'Russia',
    'SA'=>'Saudi Arabia','RS'=>'Serbia','SG'=>'Singapore','SK'=>'Slovakia','SI'=>'Slovenia',
    'ZA'=>'South Africa','KR'=>'South Korea','ES'=>'Spain','SE'=>'Sweden','CH'=>'Switzerland',
    'SY'=>'Syria','TW'=>'Taiwan','TH'=>'Thailand','TN'=>'Tunisia','TR'=>'Turkey',
    'UA'=>'Ukraine','AE'=>'UAE','GB'=>'United Kingdom','US'=>'United States',
    'UY'=>'Uruguay','VN'=>'Vietnam','YE'=>'Yemen',
];
$selectedCountries = old('countries', $zone->countries ?? []);
$selectedMethods = old('methods', isset($zone) ? $zone->methods->pluck('id')->toArray() : []);
@endphp

<div class="form-grid" style="margin-bottom:1rem;">
    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label">Zone Name *</label>
        <input type="text" name="name" class="form-input" required value="{{ old('name', $zone->name ?? '') }}" placeholder="e.g. Middle East, Europe">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label">Countries * <span style="font-weight:400;text-transform:none;">(hold Ctrl/Cmd to select multiple)</span></label>
        <select name="countries[]" multiple class="form-select" style="min-height:180px;" required>
            @foreach($isoCountries as $code => $name)
                <option value="{{ $code }}" {{ in_array($code, $selectedCountries) ? 'selected' : '' }}>{{ $code }} — {{ $name }}</option>
            @endforeach
        </select>
        @error('countries')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label">Regions/States <span style="font-weight:400;text-transform:none;">(comma-separated, optional)</span></label>
        <input type="text" name="regions" class="form-input"
               value="{{ old('regions', isset($zone) && $zone->regions ? implode(', ', $zone->regions) : '') }}"
               placeholder="e.g. CA, NY, TX">
    </div>
</div>

@if(isset($methods) && $methods->count())
<div class="card" style="margin-bottom:1rem;">
    <p class="section-title">Assign Shipping Methods</p>
    <table>
        <thead>
            <tr>
                <th>Assign</th>
                <th>Method</th>
                <th>Type</th>
                <th>Default Rate</th>
                <th>Rate Override ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($methods as $method)
            @php
                $isAssigned = in_array($method->id, $selectedMethods);
                $override = isset($zone) ? optional($zone->methods->firstWhere('id', $method->id))->pivot->rate_override : null;
                $override = old("rate_overrides.{$method->id}", $override);
            @endphp
            <tr>
                <td>
                    <input type="checkbox" name="methods[]" value="{{ $method->id }}"
                           {{ $isAssigned ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--orange);">
                </td>
                <td style="font-weight:700;">{{ $method->name }}</td>
                <td><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$method->type)) }}</span></td>
                <td>{{ $method->formatted_rate }}</td>
                <td style="width:140px;">
                    <input type="number" name="rate_overrides[{{ $method->id }}]" class="form-input"
                           step="0.01" min="0" value="{{ $override }}" placeholder="—">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
