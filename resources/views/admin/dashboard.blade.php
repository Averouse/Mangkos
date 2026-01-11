<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 text-gray-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-xl h-full">
        <div class="p-6 border-b border-gray-700 flex items-center gap-3 flex-shrink-0">
            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">A</div>
            <div>
                <span class="text-lg font-bold block leading-none">Admin Panel</span>
                <span class="text-xs text-gray-400 tracking-wider uppercase">Mangkos</span>
            </div>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="#" onclick="switchTab('dashboard')" id="nav-dashboard" class="flex items-center gap-3 px-4 py-3 bg-gray-800 rounded-xl font-medium transition text-green-400 border-l-4 border-green-400">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>
            
            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider px-4">Identity Verification</div>
            
            <a href="#" onclick="switchTab('pending-users')" id="nav-pending-users" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-user-graduate w-5 group-hover:text-blue-400"></i> User KTM
                <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingUsers->where('role', 'user')->count() }}</span>
            </a>
            
            <a href="#" onclick="switchTab('pending-owners')" id="nav-pending-owners" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-id-card w-5 group-hover:text-orange-400"></i> Owner KTP
                <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingUsers->where('role', 'owner')->count() }}</span>
            </a>
            
            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider px-4">Property Management</div>
            
            <a href="#" onclick="switchTab('kost-verification')" id="nav-kost-verification" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-building w-5 group-hover:text-purple-400"></i> Kost Verification
                <span class="ml-auto bg-purple-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingKostCount ?? 0 }}</span>
            </a>
            
            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider px-4">Approved Items</div>

            <a href="#" onclick="switchTab('approved-users')" id="nav-approved-users" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-user-check w-5 group-hover:text-green-400"></i> Approved Students
                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $approvedUsers->where('role', 'user')->count() }}</span>
            </a>

            <a href="#" onclick="switchTab('approved-owners')" id="nav-approved-owners" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-user-tie w-5 group-hover:text-green-400"></i> Approved Owners
                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $approvedUsers->where('role', 'owner')->count() }}</span>
            </a>

            <a href="#" onclick="switchTab('approved-kosts')" id="nav-approved-kosts" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl font-medium transition group">
                <i class="fas fa-building-check w-5 group-hover:text-green-400"></i> Approved Kosts
                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $approvedKosts->count() ?? 0 }}</span>
            </a>

        </nav>


        <div class="p-4 border-t border-gray-700 flex-shrink-0">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2 text-red-400 hover:text-red-300 w-full transition font-medium text-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 p-6 flex justify-between items-center sticky top-0 z-10">
            <h2 class="text-2xl font-bold text-gray-800" id="page-title">Dashboard Overview</h2>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-700">Super Admin</p>
                    <p class="text-xs text-green-500 flex items-center justify-end gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Online
                    </p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-200">
            </div>
        </header>

        <div class="p-8">
            
            <!-- DASHBOARD VIEW -->
            <div id="view-dashboard" class="block">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-gray-400 text-xs font-bold uppercase mb-2">Total Users</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</div>
                        <div class="text-xs text-gray-500 mt-1">Registered users</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-gray-400 text-xs font-bold uppercase mb-2">Students</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $studentCount }}</div>
                        <div class="text-xs text-gray-500 mt-1">Role: user</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-yellow-500">
                        <div class="text-gray-400 text-xs font-bold uppercase mb-2">Pending</div>
                        <div class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</div>
                        <div class="text-xs text-gray-400 mt-1">Need verification</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-green-500">
                        <div class="text-gray-400 text-xs font-bold uppercase mb-2">Approved</div>
                        <div class="text-3xl font-bold text-green-600">{{ $approvedCount }}</div>
                        <div class="text-xs text-gray-400 mt-1">Verified users</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <i class="fas fa-shield-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Admin Control Center</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Manage user verifications and monitor system activity. Click on sidebar menu to manage users.</p>
                </div>
            </div>

            <!-- PENDING USERS VIEW -->
            <div id="view-pending" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($pendingUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 font-bold text-xs uppercase border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">User Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">Campus Info</th>
                                        <th class="px-6 py-4">Registered</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pendingUsers as $user)
                                    <tr class="hover:bg-yellow-50/30 transition" id="user-{{ $user->id }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->campus ?? 'Not set' }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->major ?? 'No major' }} • {{ $user->year ?? 'No year' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                    <i class="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <button onclick="approveUser({{ $user->id }})" class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                                <button onclick="rejectUser({{ $user->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-check-circle text-6xl text-green-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Pending Users</h3>
                            <p class="text-gray-500">All users have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- APPROVED USERS VIEW -->
            <div id="view-approved" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($approvedUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 font-bold text-xs uppercase border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">User Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">ID Photo</th>
                                        <th class="px-6 py-4">Approved</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($approvedUsers as $user)
                                    <tr class="hover:bg-green-50/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                @if($user->id_card_photo)
                                                    <img src="/uploads/ktp/{{ $user->id_card_photo }}" class="w-12 h-8 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->id_card_photo }}", '{{ $user->name }} - KTP')" title="KTP">
                                                @endif
                                                @if($user->selfie_with_id_photo)
                                                    <img src="/uploads/ktp/{{ $user->selfie_with_id_photo }}" class="w-12 h-8 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->selfie_with_id_photo }}', '{{ $user->name }} - Selfie with KTP')" title="Selfie with KTP">
                                                @endif
                                                @if(!$user->id_card_photo && !$user->selfie_with_id_photo)
                                                    <span class="text-xs text-gray-400">No photos uploaded</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->updated_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                    <i class="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs font-bold">
                                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Approved Users</h3>
                            <p class="text-gray-500">No users have been approved yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PENDING USERS (KTM) VIEW -->
            <div id="view-pending-users" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @php $pendingUsersList = $pendingUsers->where('role', 'user'); @endphp
                    @if($pendingUsersList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-blue-50 text-blue-700 font-bold text-xs uppercase border-b border-blue-100">
                                    <tr>
                                        <th class="px-6 py-4">Student Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">Campus Info</th>
                                        <th class="px-6 py-4">KTM Photo</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pendingUsersList as $user)
                                    <tr class="hover:bg-blue-50/30 transition" id="user-{{ $user->id }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-blue-600">Student</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->campus ?? 'Not set' }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->major ?? 'No major' }} • {{ $user->year ?? 'No year' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-1">
                                                @if($user->id_card_photo)
                                                    <img src="/uploads/ktm/{{ $user->id_card_photo }}" class="w-8 h-6 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktm/{{ $user->id_card_photo }}', '{{ $user->name }} - KTM Card')" title="KTM Card">
                                                @endif
                                                @if($user->selfie_with_id_photo)
                                                    <img src="/uploads/ktm/{{ $user->selfie_with_id_photo }}" class="w-8 h-6 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktm/{{ $user->selfie_with_id_photo }}', '{{ $user->name }} - Selfie with KTM')" title="Selfie">
                                                @endif
                                                @if(!$user->id_card_photo && !$user->selfie_with_id_photo)
                                                    <span class="text-xs text-gray-400">No photos</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                    <i class="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <button onclick="approveUser({{ $user->id }})" class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                                <button onclick="rejectUser({{ $user->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-user-graduate text-6xl text-blue-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Pending Students</h3>
                            <p class="text-gray-500">All student KTM verifications have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PENDING OWNERS (KTP) VIEW -->
            <div id="view-pending-owners" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @php $pendingOwnersList = $pendingUsers->where('role', 'owner'); @endphp
                    @if($pendingOwnersList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-orange-50 text-orange-700 font-bold text-xs uppercase border-b border-orange-100">
                                    <tr>
                                        <th class="px-6 py-4">Owner Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">KTP Photo</th>
                                        <th class="px-6 py-4">Registered</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pendingOwnersList as $user)
                                    <tr class="hover:bg-orange-50/30 transition" id="user-{{ $user->id }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f97316&color=fff" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-orange-600">Owner</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-1">
                                                @if($user->id_card_photo)
                                                    <img src="/uploads/ktp/{{ $user->id_card_photo }}" class="w-8 h-6 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->id_card_photo }}', '{{ $user->name }} - KTP Card')" title="KTP Card">
                                                @endif
                                                @if($user->selfie_with_id_photo)
                                                    <img src="/uploads/ktp/{{ $user->selfie_with_id_photo }}" class="w-8 h-6 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->selfie_with_id_photo }}', '{{ $user->name }} - Selfie with KTP')" title="Selfie">
                                                @endif
                                                @if(!$user->id_card_photo && !$user->selfie_with_id_photo)
                                                    <span class="text-xs text-gray-400">No photos</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                    <i class="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <button onclick="approveUser({{ $user->id }})" class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                                <button onclick="rejectUser({{ $user->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-id-card text-6xl text-orange-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Pending Owners</h3>
                            <p class="text-gray-500">All owner KTP verifications have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOST VERIFICATION VIEW -->
            <div id="view-kost-verification" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if(isset($pendingKosts) && $pendingKosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-purple-50 text-purple-700 font-bold text-xs uppercase border-b border-purple-100">
                                    <tr>
                                        <th class="px-6 py-4">Kost Info</th>
                                        <th class="px-6 py-4">Owner</th>
                                        <th class="px-6 py-4">Location</th>
                                        <th class="px-6 py-4">Price & Rooms</th>
                                        <th class="px-6 py-4">Submitted</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pendingKosts as $kost)
                                    <tr class="hover:bg-purple-50/30 transition" id="kost-{{ $kost->id }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-building text-purple-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $kost->name }}</p>
                                                    <p class="text-xs text-purple-600">{{ ucfirst($kost->type) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $kost->owner->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->owner->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ Str::limit($kost->address, 30) }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->total_rooms }} kamar</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $kost->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <button onclick="viewKostDetails({{ $kost->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                    <i class="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <button onclick="approveKost({{ $kost->id }})" class="bg-purple-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-600 transition">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                                <button onclick="rejectKost({{ $kost->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-building text-6xl text-purple-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Pending Kosts</h3>
                            <p class="text-gray-500">All kost properties have been verified.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- APPROVED USERS (KTM) VIEW -->
            <div id="view-approved-users" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @php $approvedUsersList = $approvedUsers->where('role', 'user'); @endphp
                    @if($approvedUsersList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-green-50 text-green-700 font-bold text-xs uppercase border-b border-green-100">
                                    <tr>
                                        <th class="px-6 py-4">Student Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">Campus Info</th>
                                        <th class="px-6 py-4">KTM Photo</th>
                                        <th class="px-6 py-4">Approved Date</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($approvedUsersList as $user)
                                    <tr class="hover:bg-green-50/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-green-600">Verified Student</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->campus ?? 'Not set' }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->major ?? 'No major' }} • {{ $user->year ?? 'No year' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($user->photo)
                                                <img src="/uploads/ktm/{{ $user->photo }}" class="w-16 h-10 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktm/{{ $user->photo }}', '{{ $user->name }} - KTM')">
                                            @else
                                                <span class="text-xs text-gray-400">No KTM</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->updated_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                <i class="fas fa-eye mr-1"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-user-graduate text-6xl text-green-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Approved Students</h3>
                            <p class="text-gray-500">No students have been approved yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- APPROVED OWNERS VIEW -->
            <div id="view-approved-owners" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @php $approvedOwnersList = $approvedUsers->where('role', 'owner'); @endphp
                    @if($approvedOwnersList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-green-50 text-green-700 font-bold text-xs uppercase border-b border-green-100">
                                    <tr>
                                        <th class="px-6 py-4">Owner Info</th>
                                        <th class="px-6 py-4">Contact</th>
                                        <th class="px-6 py-4">KTP Photo</th>
                                        <th class="px-6 py-4">Approved Date</th>
                                        <th class="px-6 py-4">Kost Count</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($approvedOwnersList as $user)
                                    <tr class="hover:bg-green-50/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-green-600">Verified Owner</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                @if($user->id_card_photo)
                                                    <img src="/uploads/ktp/{{ $user->id_card_photo }}" class="w-12 h-8 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->id_card_photo }}", '{{ $user->name }} - KTP')" title="KTP">
                                                @endif
                                                @if($user->selfie_with_id_photo)
                                                    <img src="/uploads/ktp/{{ $user->selfie_with_id_photo }}" class="w-12 h-8 object-cover rounded border cursor-pointer hover:opacity-75 transition" onclick="openPhotoModal('/uploads/ktp/{{ $user->selfie_with_id_photo }}', '{{ $user->name }} - Selfie with KTP')" title="Selfie with KTP">
                                                @endif
                                                @if(!$user->id_card_photo && !$user->selfie_with_id_photo)
                                                    <span class="text-xs text-gray-400">No photos uploaded</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $user->updated_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $user->kosts->count() ?? 0 }}</p>
                                            <p class="text-xs text-gray-500">Properties</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="viewUserDetails({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                <i class="fas fa-eye mr-1"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-id-card text-6xl text-green-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Approved Owners</h3>
                            <p class="text-gray-500">No owners have been approved yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- APPROVED KOSTS VIEW -->
            <div id="view-approved-kosts" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @if(isset($approvedKosts) && $approvedKosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-green-50 text-green-700 font-bold text-xs uppercase border-b border-green-100">
                                    <tr>
                                        <th class="px-6 py-4">Kost Info</th>
                                        <th class="px-6 py-4">Owner</th>
                                        <th class="px-6 py-4">Location</th>
                                        <th class="px-6 py-4">Price & Rooms</th>
                                        <th class="px-6 py-4">Approved Date</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($approvedKosts as $kost)
                                    <tr class="hover:bg-green-50/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-building text-green-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $kost->name }}</p>
                                                    <p class="text-xs text-green-600">{{ ucfirst($kost->type) }} • Active</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $kost->owner->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->owner->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ Str::limit($kost->address, 30) }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->available_rooms }}/{{ $kost->total_rooms }} available</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $kost->updated_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $kost->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="viewKostDetails({{ $kost->id }})" class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                                <i class="fas fa-eye mr-1"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-building text-6xl text-green-300 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No Approved Kosts</h3>
                            <p class="text-gray-500">No kost properties have been approved yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- USER DETAILS MODAL -->
        <div id="userDetailsModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl w-full max-w-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">User Details</h3>
                        <button onclick="closeUserDetails()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="userDetailsContent"></div>
                </div>
            </div>
        </div>

        <!-- KOST DETAILS MODAL -->
        <div id="kostDetailsModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm">
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl w-full max-w-4xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Kost Details</h3>
                        <button onclick="closeKostDetails()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="kostDetailsContent"></div>
                </div>
            </div>
        </div>

    </main>

    <!-- PHOTO MODAL -->
    <div id="photoModal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closePhotoModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
            <p id="modalTitle" class="text-white text-center mt-4 font-medium"></p>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all views
            document.getElementById('view-dashboard').classList.add('hidden');
            document.getElementById('view-pending').classList.add('hidden');
            document.getElementById('view-approved').classList.add('hidden');
            document.getElementById('view-pending-users').classList.add('hidden');
            document.getElementById('view-pending-owners').classList.add('hidden');
            document.getElementById('view-kost-verification').classList.add('hidden');
            document.getElementById('view-approved-users').classList.add('hidden');
            document.getElementById('view-approved-owners').classList.add('hidden');
            document.getElementById('view-approved-kosts').classList.add('hidden');

            // Show selected view
            document.getElementById('view-' + tabName).classList.remove('hidden');
            
            // Update navigation
            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('bg-gray-800', 'text-green-400', 'border-l-4', 'border-green-400');
                link.classList.add('text-gray-400');
            });
            
            document.getElementById('nav-' + tabName).classList.add('bg-gray-800', 'text-green-400', 'border-l-4', 'border-green-400');
            document.getElementById('nav-' + tabName).classList.remove('text-gray-400');
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard Overview',
                'pending': 'Pending Users',
                'approved': 'Approved Users',
                'pending-users': 'Student KTM Verification',
                'pending-owners': 'Owner KTP Verification',
                'kost-verification': 'Kost Property Verification',
                'approved-users': 'Approved Students',
                'approved-owners': 'Approved Owners', 
                'approved-kosts': 'Approved Kost Properties'
            };
            document.getElementById('page-title').textContent = titles[tabName];
        }


        function openPhotoModal(photoUrl, title) {
            document.getElementById('modalPhoto').src = photoUrl;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });

        function approveUser(userId) {
            if (confirm('Approve this user?')) {
                fetch(`/admin/users/${userId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`user-${userId}`).style.opacity = '0';
                        setTimeout(() => {
                            document.getElementById(`user-${userId}`).remove();
                            location.reload(); // Refresh to update counts
                        }, 300);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function rejectUser(userId) {
            if (confirm('Reject this user? This action cannot be undone.')) {
                fetch(`/admin/users/${userId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`user-${userId}`).style.opacity = '0';
                        setTimeout(() => {
                            document.getElementById(`user-${userId}`).remove();
                            location.reload(); // Refresh to update counts
                        }, 300);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function viewUserDetails(userId) {
            const pendingUsers = @json($pendingUsers ?? []);
            const approvedUsers = @json($approvedUsers ?? []);
            const allUsers = [...pendingUsers, ...approvedUsers];
            const user = allUsers.find(u => u.id === userId);
            
            if (!user) return;
            
            const photoFolder = user.role === 'user' ? 'ktm' : 'ktp';
            const idLabel = user.role === 'user' ? 'KTM' : 'KTP';
            
            const content = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">Personal Information</h4>
                        <div class="space-y-3">
                            <div><strong>Name:</strong> ${user.name}</div>
                            <div><strong>Email:</strong> ${user.email}</div>
                            <div><strong>Phone:</strong> ${user.phone || 'Not provided'}</div>
                            <div><strong>Role:</strong> ${user.role}</div>
                            <div><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${user.status === 'approved' ? 'bg-green-100 text-green-700' : user.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'}">${user.status}</span></div>
                            <div><strong>Registered:</strong> ${new Date(user.created_at).toLocaleDateString()}</div>
                        </div>
                        
                        ${user.role === 'user' ? `
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Campus Information</h4>
                        <div class="space-y-3">
                            <div><strong>Campus:</strong> ${user.campus || 'Not provided'}</div>
                            <div><strong>Major:</strong> ${user.major || 'Not provided'}</div>
                            <div><strong>Year:</strong> ${user.year || 'Not provided'}</div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">${idLabel} Verification Photos</h4>
                        <div class="space-y-4">
                            ${user.id_card_photo ? `
                            <div>
                                <p class="text-sm font-medium mb-2">${idLabel} Card:</p>
                                <img src="/uploads/${photoFolder}/${user.id_card_photo}" class="w-full h-40 object-cover rounded border cursor-pointer" onclick="openPhotoModal('/uploads/${photoFolder}/${user.id_card_photo}', '${user.name} - ${idLabel} Card')">
                            </div>
                            ` : `<p class="text-gray-500">No ${idLabel} photo uploaded</p>`}
                            
                            ${user.selfie_with_id_photo ? `
                            <div>
                                <p class="text-sm font-medium mb-2">Selfie with ${idLabel}:</p>
                                <img src="/uploads/${photoFolder}/${user.selfie_with_id_photo}" class="w-full h-40 object-cover rounded border cursor-pointer" onclick="openPhotoModal('/uploads/${photoFolder}/${user.selfie_with_id_photo}', '${user.name} - Selfie with ${idLabel}')">
                            </div>
                            ` : `<p class="text-gray-500">No selfie photo uploaded</p>`}
                        </div>
                    </div>
                </div>
                
                ${user.status === 'pending' ? `
                <div class="mt-8 flex gap-3 justify-end">
                    <button onclick="approveUser(${user.id})" class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition">
                        <i class="fas fa-check mr-2"></i> Approve User
                    </button>
                    <button onclick="rejectUser(${user.id})" class="bg-red-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-600 transition">
                        <i class="fas fa-times mr-2"></i> Reject User
                    </button>
                </div>
                ` : ''}
            `;
            
            document.getElementById('userDetailsContent').innerHTML = content;
            document.getElementById('userDetailsModal').classList.remove('hidden');
        }

        function closeUserDetails() {
            document.getElementById('userDetailsModal').classList.add('hidden');
        }

        function approveKost(kostId) {
            if (confirm('Approve this kost?')) {
                fetch(`/admin/kosts/${kostId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`kost-${kostId}`).style.opacity = '0';
                        setTimeout(() => {
                            document.getElementById(`kost-${kostId}`).remove();
                            location.reload();
                        }, 300);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function rejectKost(kostId) {
            if (confirm('Reject this kost?')) {
                fetch(`/admin/kosts/${kostId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`kost-${kostId}`).style.opacity = '0';
                        setTimeout(() => {
                            document.getElementById(`kost-${kostId}`).remove();
                            location.reload();
                        }, 300);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        function viewKostDetails(kostId) {
            // Find kost data from the page
            const pendingKosts = @json($pendingKosts ?? []);
            const approvedKosts = @json($approvedKosts ?? []);
            const kostData = [...pendingKosts, ...approvedKosts];
            const kost = kostData.find(k => k.id === kostId);
            
            if (!kost) return;
            
            const content = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">Basic Information</h4>
                        <div class="space-y-3">
                            <div><strong>Name:</strong> ${kost.name}</div>
                            <div><strong>Address:</strong> ${kost.address}</div>
                            <div><strong>Price:</strong> Rp ${new Intl.NumberFormat('id-ID').format(kost.price)}/month</div>
                            <div><strong>Type:</strong> ${kost.type}</div>
                            <div><strong>Total Rooms:</strong> ${kost.total_rooms}</div>
                            <div><strong>Description:</strong> ${kost.description || 'No description'}</div>
                        </div>
                        
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Owner Information</h4>
                        <div class="space-y-3">
                            <div><strong>Owner:</strong> ${kost.owner.name}</div>
                            <div><strong>Email:</strong> ${kost.owner.email}</div>
                        </div>
                        
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Facilities</h4>
                        <div class="flex flex-wrap gap-2">
                            ${kost.facilities ? kost.facilities.map(f => `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">${f}</span>`).join('') : 'No facilities listed'}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">Photos</h4>
                        <div class="grid grid-cols-2 gap-3">
                            ${kost.photos && kost.photos.length > 0 ? 
                                kost.photos.map(photo => `<img src="/uploads/kosts/${photo}" class="w-full h-32 object-cover rounded border cursor-pointer" onclick="openPhotoModal('/uploads/kosts/${photo}', '${kost.name} - Photo')">`).join('') : 
                                '<p class="text-gray-500 col-span-2">No photos uploaded</p>'
                            }
                        </div>
                        
                        ${kost.latitude && kost.longitude ? `
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Location</h4>
                        <div class="space-y-2">
                            <div><strong>Latitude:</strong> ${kost.latitude}</div>
                            <div><strong>Longitude:</strong> ${kost.longitude}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="mt-8 flex gap-3 justify-end">
                    <button onclick="approveKost(${kost.id})" class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition">
                        <i class="fas fa-check mr-2"></i> Approve Kost
                    </button>
                    <button onclick="rejectKost(${kost.id})" class="bg-red-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-600 transition">
                        <i class="fas fa-times mr-2"></i> Reject Kost
                    </button>
                </div>
            `;
            
            document.getElementById('kostDetailsContent').innerHTML = content;
            document.getElementById('kostDetailsModal').classList.remove('hidden');
        }

        function closeKostDetails() {
            document.getElementById('kostDetailsModal').classList.add('hidden');
        }

    </script>

</body>
</html>
