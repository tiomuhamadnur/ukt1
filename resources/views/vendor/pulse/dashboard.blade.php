<x-pulse>
    <div class="my-0">
        <a href="{{ route('dashboard.index') }}"
        class="inline-block bg-blue-600 text-white rounded hover:bg-blue-700">
            ← Back
        </a>
    </div>

    <livewire:pulse.servers cols="full" />

    <livewire:pulse.usage cols="4" rows="2" />

    <livewire:pulse.queues cols="4" />

    <livewire:pulse.cache cols="4" />

    <livewire:pulse.slow-queries cols="8" />

    <livewire:pulse.exceptions cols="6" />

    <livewire:pulse.slow-requests cols="6" />

    <livewire:pulse.slow-jobs cols="6" />

    <livewire:pulse.slow-outgoing-requests cols="6" />
</x-pulse>
