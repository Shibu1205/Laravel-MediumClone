
<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <x-category-tabs></x-category-tabs>

                </div>

            </div>

            <div class="mt-8 text-gray-900 ">

                <div class="space-y-6 p-6 text-gray-900">

                    @foreach($posts as $p)
                        <x-post-item :post="$p"></x-post-item>
                    @endforeach
                </div>
                 {{ $posts->onEachSide(2)->links() }}

            </div>
            

        </div>
    </div>
</x-app-layout>