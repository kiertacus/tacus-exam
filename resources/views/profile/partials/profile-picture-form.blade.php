<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Upload or change your profile picture.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Current Profile Picture -->
        <div>
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if($user->profilePicture)
                        <img src="{{ asset('storage/profile-pictures/' . $user->profilePicture->path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.7 15.01C13.9 15.01 15.08 15.1 16.23 15.27c.84.12 1.67.27 2.5.45v5.706c-.75.855-2.395 2.729-4.23 3.953zM16.6 20.573c-.766-1.464-2.201-2.91-4.132-3.628a9.971 9.971 0 00-5.937 0C7.601 17.663 6.166 19.109 5.4 20.573M20 4a4 4 0 11-8 0 4 4 0 018 0zM4 6a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <form method="post" action="{{ route('profile-picture.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="profile_picture" class="block cursor-pointer">
                    <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-blue-500 transition hover:bg-gray-800">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 group-hover:text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">Click to upload profile picture</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 5MB</p>
                    </div>
                    <input 
                        type="file" 
                        name="profile_picture" 
                        id="profile_picture"
                        accept="image/jpeg,image/png,image/gif,image/jpg"
                        class="hidden"
                        onchange="previewProfilePicture(this)"
                    >
                </label>
                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
            </div>

            <!-- Preview -->
            <div id="profilePicturePreview" class="hidden">
                <p class="text-sm font-medium text-white mb-2">Preview:</p>
                <img id="previewImage" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-700">
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button id="uploadBtn" disabled>{{ __('Upload Picture') }}</x-primary-button>

                @if($user->profilePicture)
                    <form method="post" action="{{ route('profile-picture.destroy') }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 underline text-sm" onclick="return confirm('Are you sure you want to delete your profile picture?');">
                            {{ __('Delete Picture') }}
                        </button>
                    </form>
                @endif

                @if (session('status') === 'profile-picture-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600"
                    >{{ __('Profile picture updated successfully!') }}</p>
                @endif

                @if (session('status') === 'profile-picture-deleted')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600"
                    >{{ __('Profile picture deleted!') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>

<script>
function previewProfilePicture(input) {
    const preview = document.getElementById('profilePicturePreview');
    const previewImage = document.getElementById('previewImage');
    const uploadBtn = document.getElementById('uploadBtn');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
            uploadBtn.disabled = false;
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
        uploadBtn.disabled = true;
    }
}
</script>

