@php $editing = isset($paiementsMode) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="nom"
            label="Nom"
            value="{{ old('nom', ($editing ? $paiementsMode->nom : '')) }}"
            maxlength="255"
            placeholder="Nom"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>
