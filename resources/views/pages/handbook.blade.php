@extends('layouts.app')

@section('title', 'Handbook ')

@section('content')
<!-- css assets/css/sanden/edms.css -->
</head>
<body>
<style>
 
</style>
  <div class="page-content">

  <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">EDMS</a></li>
						<li class="breadcrumb-item active" aria-current="page">Company Handbook</li>
					</ol>
				</nav>



    <div id="viewer"></div>
    <div id="spinner" style="display: none;">
  <div class="container-loader">
  <div class="loader"></div>
</div>
</div>

  </div>
<script>
(function () {
  const viewer = document.getElementById('viewer'); // your PDF container
  const alertEl = document.createElement('div');

  // Basic styled warning element (adjust / style with CSS)
  Object.assign(alertEl.style, {
    position: 'fixed',
    top: '20px',
    right: '20px',
    zIndex: 2000,
    padding: '12px 16px',
    background: 'rgba(0,0,0,0.8)',
    color: 'white',
    borderRadius: '6px',
    fontFamily: 'sans-serif',
    fontSize: '14px',
    display: 'none',
  });
  alertEl.textContent = '⚠️ Screenshot detected. This action is prohibited.';
  document.body.appendChild(alertEl);

  // show temporary warning & optional blur
  function showWarning() {
    alertEl.style.display = 'block';
    if (viewer) viewer.style.filter = 'blur(4px)';
    setTimeout(() => {
      alertEl.style.display = 'none';
      if (viewer) viewer.style.filter = '';
    }, 2400);
  }

  // try to overwrite clipboard (best-effort)
  async function tryOverwriteClipboard() {
    if (!navigator.clipboard || !navigator.clipboard.writeText) return false;
    try {
      // write a short notice — browsers may require user gesture & permission
      await navigator.clipboard.writeText('Screenshots are prohibited by policy.');
      return true;
    } catch (err) {
      // permission denied or not allowed
      return false;
    }
  }

  // report to server (best-effort)
  function reportScreenshotAttempt(detail = {}) {
    // adjust endpoint + payload as needed
    fetch('/api/log-screenshot', {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify({
        ts: new Date().toISOString(),
        ua: navigator.userAgent,
        detail,
      }),
    }).catch(() => {/* ignore network errors */});
  }

  // Key detection: key or keyCode fallback
  document.addEventListener('keydown', async (e) => {
    const isPrintKey = (e.key && e.key === 'PrintScreen') || (e.keyCode && e.keyCode === 44);
    if (!isPrintKey) return;

    // best-effort reactions
    showWarning();
    const clipboardOK = await tryOverwriteClipboard();
    reportScreenshotAttempt({ method: 'keydown', clipboardOK });
  });

  // keyup may catch some browsers that don't send keydown
  document.addEventListener('keyup', async (e) => {
    const isPrintKey = (e.key && e.key === 'PrintScreen') || (e.keyCode && e.keyCode === 44);
    if (!isPrintKey) return;

    showWarning();
    const clipboardOK = await tryOverwriteClipboard();
    reportScreenshotAttempt({ method: 'keyup', clipboardOK });
  });

  // Some snipping tools trigger 'copy' event if they place image data in clipboard.
  // Detect copy & overwrite clipboard text (best-effort).
  document.addEventListener('copy', async (e) => {
    // clear or replace clipboard text selection
    try {
      e.preventDefault(); // attempt to stop default copy
    } catch (err) { /* ignore */ }

    const clipboardOK = await tryOverwriteClipboard();
    showWarning();
    reportScreenshotAttempt({ method: 'copy', clipboardOK });
  });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
  const viewer = document.getElementById('viewer');
  const url = "{{ asset('pdf/handbook.pdf') }}"; // Laravel PDF path
  let isLoading = false;
  let currentPDF = null;
  const pageFilter = []; // e.g. [1, 3, 5] if you want specific pages


  try {
   

    // Load PDF
    const loadingTask = pdfjsLib.getDocument(url);
    const pdf = await loadingTask.promise;
    currentPDF = pdf;
    const numPages = pdf.numPages;

    // Determine which pages to render
    let pagesToRender = [];
    if (pageFilter && pageFilter.length > 0) {
      pagesToRender = pageFilter.filter(p => p >= 1 && p <= numPages);
      if (pagesToRender.length === 0) {
        viewer.innerHTML = `<p style="color:red;">No valid page numbers selected.</p>`;
        Swal.close();
        isLoading = false;
        return;
      }
    } else {
      pagesToRender = Array.from({ length: numPages }, (_, i) => i + 1);
    }

    // Render each page
    for (const pageNumber of pagesToRender) {
      const page = await pdf.getPage(pageNumber);
      const scale = 1.3;
      const viewport = page.getViewport({ scale });

      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      await page.render({ canvasContext: context, viewport }).promise;


      // Append to viewer
      viewer.appendChild(canvas);
    }

    Swal.close();
    viewer.scrollTo({ top: 0, behavior: 'smooth' });

  } catch (error) {
    viewer.innerHTML = '❌ Failed to load PDF.';
    console.error(error);
    Swal.fire('Error', 'Failed to load PDF.', 'error');
  } finally {
    isLoading = false;
  }
});
</script>
@endsection