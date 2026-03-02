<!DOCTYPE html>
<html>
<head>
    <title>Cold Chain Service Report</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }

        h1, h2 { text-align: center; margin-bottom: 30px; }

        .content { margin-bottom: 20px; }

        /* Grid for full pages (2x2) */
        .images-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        /* Last page container for proportional spacing */
        .last-page-container {
            display: flex;
            flex-direction: column; /* stack rows vertically */
            justify-content: space-evenly; /* even vertical spacing */
            align-items: center; /* center horizontally */
            min-height: calc(100vh - 200px); /* adjust to remaining page space */
            flex-wrap: wrap;
        }

        .last-page-row {
            display: flex;
            justify-content: center; /* center images horizontally in each row */
            gap: 10px;
        }

        .image-container {
            text-align: center;
            border: 1px solid #ccc;
            padding: 5px;
            margin: 5px;
        }

        .image-container img {
            width: 100%;
            max-width: 250px;
            height: auto;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Attachments header */
        .attachments-header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/Sanden_Logo_SCP2_.png') }}" alt="Sanden Logo" style="width:150px; display:block; margin:auto;">
        <h1>Cold Chain Service Report</h1>
    </div>

    <div class="content">
        {!! nl2br(e($content)) !!}
    </div>

    @if(!empty($images))
        {{-- Start attachments on new page --}}
        <div class="page-break"></div>
        <div class="attachments-header">Attachments</div>

        @foreach(array_chunk($images, 4) as $chunk)
            @if($loop->last && count($chunk) < 4)
                {{-- Last page with fewer than 4 images, proportional spacing --}}
                <div class="last-page-container">
                    @foreach(array_chunk($chunk, 2) as $row)
                        <div class="last-page-row">
                            @foreach($row as $img)
                                <div class="image-container">
                                    <img src="{{ public_path('uploads/' . $img) }}" alt="Image">
                                    <p>{{ $img }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Normal pages with 4 images (2x2 grid) --}}
                <div class="images-grid">
                    @foreach($chunk as $img)
                        <div class="image-container">
                            <img src="{{ public_path('uploads/' . $img) }}" alt="Image">
                            <p>{{ $img }}</p>
                        </div>
                    @endforeach
                </div>
                @if(!$loop->last)
                    <div class="page-break"></div>
                @endif
            @endif
        @endforeach
    @endif
</body>
</html>
