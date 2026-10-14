<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl mb-4">{{ $post->title }}</h1>
                <div class="flex gap-4">
                    <x-user-avatar :user="$post->user"/>
                </div>
                <div>
                     <x-follow-ctr :user="$post->user" class="flex gap-2">
                        <a href="{{ route('profile.show', $post->user) }}" class="hover:underline">{{ $post->user->name }}</a>
                        &middot;
                        <a href="#" @click.prevent="follow()" class="text-emerald-500 hover:underline" x-text="following ? 'Unfollow' : 'Follow'">Follow</a>
                    </x-follow-ctr>

                    <div class="flex gap-2 text-small text-grey-500">
                        {{ $post->readTime() }} min read
                        &middot;
                        {{ $post->getcreated_at() }}

                    </div>
                    
                    @if ($post->user_id===Auth::id())
                    <div class="py-4 mt-8 border-t border-b border-gray-200">
                        <x-primary-button href="{{ route('posts.edit', $post->slug) }}">
                            Edit Post
                        </x-primary-button>

                        <form class='inline-block' action="{{ route('posts.destroy',$post) }}" method="post">
                            @csrf
                            @method('delete')
                        </form>

                        <x-danger-button>
                            Delete Post
                        </x-danger-button>
                    </div>
                    @endif
                    <div class="mt-8 p-4 border-t border-b">
                        <x-clap-button :post="$post" />

                        

                    </div>

                    <div class="mt-6">
                        @if ($post->image)
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="rounded-lg w-full">
                        @endif
                        <div class="mt-4">
                            {{ $post->content}}
                        </div>
                    </div>

                </div>

                <div class="mt-8">
                    <span class="px-4 py-2 bg-gray-300 rounded-xl">
                        {{ $post->category->title }}
                    </span> 
                    
                </div>

            </div>
        </div>
    </div>
</x-app-layout>