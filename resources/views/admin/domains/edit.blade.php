@extends('layouts.admin')
@section('admin_title', 'Edit '.$domain->name)
@section('admin_subtitle', 'Update career domain name and description shown to students.')

@section('admin')
<form method="POST" action="{{ route('admin.domains.update', $domain) }}" class="adm-card adm-form">
    @csrf
    @method('PUT')
    <div class="adm-field">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $domain->name) }}" required class="adm-input">
    </div>
    <div class="adm-field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" class="adm-input">{{ old('description', $domain->description) }}</textarea>
    </div>
    <button type="submit" class="adm-btn adm-btn-primary">Save changes</button>
</form>
@endsection
