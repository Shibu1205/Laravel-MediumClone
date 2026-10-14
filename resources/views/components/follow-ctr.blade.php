@props(['user'])

    <div {{ $attributes }} x-data="{
      following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
      followersCount: {{ $user->followers()->count() }},
      async follow() {
        try {
          const response = await fetch('/follow/{{ $user->id }}', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'X-Requested-With': 'XMLHttpRequest',
            },
          });

          if (!response.ok) {
            throw new Error(`Follow request failed: ${response.status}`);
          }

          const data = await response.json();
          this.following = !this.following;
          this.followersCount = data.followersCount;
        } catch (error) {
          console.error(error);
        }
      },
    }" class="w-[320px] border-1">

                            {{ $slot }}
</div>
