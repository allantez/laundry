<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $branch->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>City</label>
    <input type="text" name="city" value="{{ old('city', $branch->city ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label>Manager</label>
    <select name="manager_id" class="form-control">
        <option value="">Select Manager</option>
        @foreach ($managers as $manager)
            <option value="{{ $manager->id }}" @selected(old('manager_id', $branch->manager_id ?? '') == $manager->id)>
                {{ $manager->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-check mb-2">
    <input type="checkbox" name="is_active" class="form-check-input" value="1" @checked(old('is_active', $branch->is_active ?? true))>
    <label class="form-check-label">Active</label>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_main_branch" class="form-check-input" value="1" @checked(old('is_main_branch', $branch->is_main_branch ?? false))>
    <label class="form-check-label">Main Branch</label>
</div>
