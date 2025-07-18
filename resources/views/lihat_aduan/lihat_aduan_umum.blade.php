{{-- Template Kerangka Site --}}
@extends('layout.app')

{{-- Title Site --}}
@section('title', 'Lihat Aduan')

{{-- Isi Konten --}}
@section('content')
    <div class="container py-4">

    <!-- Tab -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="#">Aduan Umum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/aduan-anda') }}">Aduan Anda</a>
        </li>
    </ul>

    <!-- Search dan Sorting -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <input id="inputSearch" type="text" class="form-control w-100 w-md-50" placeholder="Search..." value="{{ request('searchKeyword') }}"
        onkeydown="if (event.key === 'Enter') searchOnly()">
        <div class="btn-group align-self-end">
            <a href="{{ route('aduan-umum', ['filterType' => 'terbaru']) }}">
              <button id="btnTerbaru" class="btn btn-outline-primary btn-sm">Terbaru</button>
            </a>
            <a href="{{ route('aduan-umum', ['filterType' => 'teratas']) }}">
              <button id="btnTeratas" class="btn btn-primary btn-sm">Teratas</button>
            </a>
        </div>
    </div>
    
    <h5 class="fw-bold">Aduan Umum</h5>

    <!-- Aduan Cards -->
    @foreach ($datas as $data)
    <div class="card mb-3 shadow-sm border-0 rounded-3">
        <div class="row g-0 align-items-center">
      
          <!-- Kolom Voting -->
          <div class="col-auto text-center px-3 py-4 border-end">
            <!-- Form Upvote -->
            <form action="{{ route('aduan-umum') }}" method="POST" style="display:inline;">
              @csrf
              <input type="hidden" name="complaint_id" value="{{ $data->complaint_complaint_id }}">
              <input type="hidden" name="vote_type" value="upvote">
              <button type="submit" class="btn p-0 border-0 bg-transparent">
                <i data-feather="chevrons-up" class="text-warning"></i>
              </button>
            </form>

            <!-- Jumlah Vote -->
            <div class="fw-bold">{{ $data->total_votes ?? 0 }}</div>

            <!-- Form Downvote -->
            <form action="{{ route('aduan-umum') }}" method="POST" style="display:inline;">
              @csrf
              <input type="hidden" name="complaint_id" value="{{ $data->complaint_complaint_id }}">
              <input type="hidden" name="vote_type" value="downvote">
              <button type="submit" class="btn p-0 border-0 bg-transparent">
                <i data-feather="chevrons-down" class="text-warning"></i>
              </button>
            </form>
          </div>
      
          <!-- Kolom Profil -->
          <div class="col-auto px-3 py-3">
            <img src="{{ $data->profile_picture 
                          ? asset('profile_uploads/' . $data->profile_picture) 
                          : asset('profile_uploads/profile_default.png') }}" 
                 class="rounded-circle border" 
                 alt="User" 
                 style="width: 50px; height: 50px; object-fit: cover;">
          </div>
      
          <!-- Konten Utama -->
          <div class="col">
            <div class="card-body py-3 px-3">
      
              <!-- Judul -->
              <a href="#" onclick="submitComplaint({{ $data->complaint_complaint_id }})" class="text-decoration-none">
                <h6 class="fw-bold text-dark mb-2">{{ $data->complaint_title }}</h6>
              </a>

              <form id="complaintForm" method="GET" action="{{ route('aduan-detail') }}">
                @csrf
                <input type="hidden" name="complaint_id" id="complaint_id">
              </form>
      
              <!-- Deskripsi -->
              <p class="text-muted small mb-2" style="line-height: 1.4;">
                {{ $data->complaint_content }} 
              </p>
      
              <!-- Info Bar -->
              <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                <span>{{ \Carbon\Carbon::parse($data->complaint_created_at)->format('d/m/Y') }}</span>
                <span class="badge bg-primary">{{ $data->proses }}</span>
                <span class="badge bg-warning text-dark">{{ $data->complaint_role ?? 'draft' }}</span>
                <span class="me-1">{{ $data->name ?? 'Anonim' }}</span>
                <span class="ms-auto me-4"><i data-feather="message-square" class="text-dark fs-3" style="scale: 0.7;"></i>{{ $data->total_comments ?? 0 }}</span>
              </div>
      
            </div>
          </div>
      
        </div>
      </div>
      
    @endforeach
</div>
@endsection

@push('script')
<script>
  axios.defaults.headers.common['X-CSRF-TOKEN'] = 
  document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function submitComplaint(complaintId) {
      document.getElementById('complaint_id').value = complaintId;
      document.getElementById('complaintForm').submit();
  }

  function searchOnly() {
        const keyword = document.getElementById('inputSearch').value;
        const url = `/aduan-umum?searchKeyword=${encodeURIComponent(keyword)}`;
        window.location.href = url;
    }
</script>
@endpush

