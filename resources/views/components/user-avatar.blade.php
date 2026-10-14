@props(['user'])
    @if ($user->image)
        <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full">

    @else
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTyzgHGldRmy6F7rY2C5appVqQsqtlynZRWGn3kpO--7w&s=10"
            alt="dummy avatar" class="w-16 h-16 rounded-full">
    @endif
