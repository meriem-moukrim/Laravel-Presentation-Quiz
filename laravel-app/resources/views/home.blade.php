@extends('layouts.app')

@section('content')
    <div class="content">
        <h1>Présentation : Routage et Middleware dans Laravel 12</h1>

        @foreach($sections as $section)
            <section id="{{ $section['id'] }}">
                <h2 id="{{ $section['id'] }}-title">{{ $section['title'] }}</h2>

                @foreach($section['content'] as $block)

                    @if($block['type'] === 'paragraph')
                        <p>{!! $block['text'] !!}</p>

                    @elseif($block['type'] === 'list')
                        @if(isset($block['ordered']) && $block['ordered'])
                            <ol>
                                @foreach($block['items'] as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ol>
                        @else
                            <ul>
                                @foreach($block['items'] as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        @endif

                    @elseif($block['type'] === 'subheader')
                        <h3>{!! $block['text'] !!}</h3>

                    @elseif($block['type'] === 'subsubheader')
                        <h4>{!! $block['text'] !!}</h4>

                    @elseif($block['type'] === 'quote')
                        <blockquote>{!! $block['text'] !!}</blockquote>

                    @elseif($block['type'] === 'code')
                        <pre><code class="language-{{ $block['language'] }}">{{ $block['code'] }}</code></pre>

                    @elseif($block['type'] === 'separator')
                        <hr />

                    @elseif($block['type'] === 'table')
                        <table class="{{ $block['className'] ?? '' }}">
                            <thead>
                                <tr>
                                    @foreach($block['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($block['rows'] as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td>
                                                @if(is_array($cell) && isset($cell['isBadge']) && $cell['isBadge'])
                                                    <span class="method-badge highlight-red">{{ $cell['content'] }}</span>
                                                @elseif(is_array($cell))
                                                    {{-- Fallback for other object types if any --}}
                                                    {{ $cell['content'] ?? '' }}
                                                @else
                                                    {!! $cell !!}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                @endforeach
            </section>
        @endforeach
    </div>
@endsection