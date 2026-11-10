@foreach ($navCategories as $category)
    <li>
        <details>
            <summary>{{ $category->name }}</summary>
            <ul class="bg-base-100 p-2 w-40 z-1">
                @foreach ($category->children as $child)
                    <li><span>{{ $child->name }}</span></li>
                @endforeach
            </ul>
        </details>
    </li>
@endforeach
