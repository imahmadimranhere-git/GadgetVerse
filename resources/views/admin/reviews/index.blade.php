<x-app-layout>

    <h2 class="fw-bold mb-4">
        <i class="bi bi-star-fill text-warning"></i> Reviews Moderation
    </h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Status filter tabs --}}
    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.reviews.index') }}"
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
               class="btn btn-sm {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                Pending
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
               class="btn btn-sm {{ request('status') === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                Approved
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}"
               class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">
                Rejected
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td class="fw-semibold">{{ $review->product->name }}</td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    {{-- Star rating display --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }} small"></i>
                                    @endfor
                                </td>
                                <td>
                                    <span class="small">{{ Str::limit($review->comment ?? '—', 40) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$review->status] }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if ($review->status !== 'approved')
                                            <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($review->status !== 'rejected')
                                            <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Reject">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

</x-app-layout>