<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Primary Button</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-white p-8 font-sans text-slate-900">
    <main class="mx-auto max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold">Bootstrap primary button with Tailwind</h1>
        <p>This button recreates Bootstrap 5.3's primary button using only Tailwind utility classes.</p>

        <div class="flex flex-wrap items-center gap-4">
            <button type="button"
                class="inline-block cursor-pointer select-none rounded-md border border-[#0d6efd] bg-[#0d6efd] px-3 py-1.5 text-center align-middle text-base leading-6 font-normal text-white transition-[color,background-color,border-color,box-shadow] duration-150 ease-in-out enabled:hover:border-[#0a58ca] enabled:hover:bg-[#0b5ed7] focus-visible:border-[#0a58ca] focus-visible:bg-[#0b5ed7] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#3184fd]/50 enabled:active:border-[#0a53be] enabled:active:bg-[#0a58ca] disabled:pointer-events-none disabled:opacity-[0.65] motion-reduce:transition-none">
                Primary
            </button>
            <button type="button" disabled
                class="inline-block cursor-pointer select-none rounded-md border border-[#0d6efd] bg-[#0d6efd] px-3 py-1.5 text-center align-middle text-base leading-6 font-normal text-white transition-[color,background-color,border-color,box-shadow] duration-150 ease-in-out enabled:hover:border-[#0a58ca] enabled:hover:bg-[#0b5ed7] focus-visible:border-[#0a58ca] focus-visible:bg-[#0b5ed7] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#3184fd]/50 enabled:active:border-[#0a53be] enabled:active:bg-[#0a58ca] disabled:pointer-events-none disabled:opacity-[0.65] motion-reduce:transition-none">
                Disabled primary
            </button>
        </div>

        <ul class="list-disc space-y-2 pl-5">
            <li>Hover over Primary to see the darker blue background.</li>
            <li>Press and hold the button to see the active state.</li>
            <li>Press Tab to focus the button and see the blue focus ring.</li>
            <li>The disabled button cannot be clicked or focused with Tab.</li>
        </ul>
        <p>
            Compare with the
            <a href="https://getbootstrap.com/docs/5.3/components/buttons/" class="text-blue-700 underline">Bootstrap button documentation</a>.
        </p>
    </main>
</body>
</html>
