<div class="mt-8 flex flex-col relative z-10">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden bg-white jojo-border jojo-shadow">
                <table class="min-w-full divide-y-4 divide-slate-900 border-collapse">
                    <thead class="bg-fuchsia-200">
                        <tr>
                            {{ $thead }}
                        </tr>
                    </thead>
                    <tbody class="divide-y-4 divide-slate-900 bg-white">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
