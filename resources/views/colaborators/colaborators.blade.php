<x-layout-app page-title="Colaborators">

    <div class="w-100 p-4">

        <h3>All colaborators</h3>

        <hr>

        <!-- table -->

        @if ($colaborators->count() === 0)
            <div class="text-center my-5">
                <p>No colaborators found.</p>

                <a class="btn btn-primary" href="{{ route('colaborators.rh.management.new')}}">Create a new colaborator</a>
            </div>

            @else
                <div class="mb-3">
                    <a href="{{ route('colaborators.rh.management.new') }}" class="btn btn-primary">Create a new colaborator</a>
                </div>
                
                <table class="table" id="table">
                    <thead class="table-dark">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Admission date</th>
                        <th>Salary</th>
                        <th></th>
                    </thead>
                    <tbody>

                        @foreach ($colaborators as $colaborator)
                            <tr>
                                <td>{{ $colaborator->name }}</td>
                                <td>{{ $colaborator->email }}</td>
                                <td>
                                    @empty($colaborator->email_verified_at)
                                        <span class='badge bg-danger'>No</span>
                                        @else
                                            <span class='badge bg-success'>Yes</span>
                                    @endempty
                                </td>
                                <td>{{ $colaborator->department?->name ?? 'Não informado'}}</td>
                                <td>{{ $colaborator->role }}</td>
                                <td>{{ $colaborator->detail?->admission_date ?? 'Não informado' }}</td>
                                {{-- @php
                                    $permissions = json_decode($colaborator->permissions);
                                @endphp --}}

                                {{-- <td>{{ implode(', ', $permissions) }}</td> --}}
                                <td>{{ $colaborator->detail?->salary ?? 'Não informado' }} $</td>

                                <td>
                                    <div class='d-flex gap-3 justify-content-end'>
                                        @empty($colaborator->deleted_at)
                                            <a href="{{ route('colaborators.rh.management.edit', $colaborator->id) }}" class="btn btn-sm btn-outline-dark ms-3"><i class="fa-regular fa-edit me-2"></i>Edit</a>
                                            <a href="{{ route('colaborators.rh.management.delete', $colaborator->id) }}" class="btn btn-sm btn-outline-dark ms-3"><i class="fa-regular fa-trash-can me-2"></i>Delete</a>
                                            <a href="{{ route('colaborators.rh.management.details', $colaborator->id) }}" class="btn btn-sm btn-outline-dark ms-3"><i class="fas fa-eye me-2"></i>Details</a>
                                            @else
                                                <a href="{{ route('colaborators.rh.management.restore', $colaborator->id) }}" class="btn btn-sm btn-outline-dark ms-3"><i class="fa-solid fa-trash-arrow-up me-2"></i>Restore</a>
                                        @endempty
                                    </div>
                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
        @endif

</x-layout-app>
