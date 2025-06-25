<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"/>

  <style>
    body {
        background-color: #f9fafb;
        margin: 0;
    }
    .sidebar {
        height: 100vh;
        background-color: #2d2f33;
        color: white;
        padding: 20px;
        width: 250px;
    }
    .sidebar a {
        color: white;
        display: block;
        padding: 10px 15px;
        margin-bottom: 8px;
        border-radius: 8px;
        text-decoration: none;
    }
    .sidebar a.active,
    .sidebar a:hover {
        background-color: #7c3aed;
    }
    .navbar-custom {
        background-color: #ffffff;
        padding: 15px 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <h5 class="mb-4">SKA Dev</h5>
      <a href="/admin/dashboard2">Dashboard</a>
      <a href="#">User</a>
      <a href="#">Article</a>
      <a href="/admin/dashboard" class="active">Module</a>
      <a href="#">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <!-- Header -->
      <div class="navbar-custom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="fas fa-bars me-3"></i>
          <h4 class="mb-0">Module List</h4>
        </div>
        <div>
          <span class="me-2">admin</span>
          <i class="fas fa-user-circle fa-lg"></i>
        </div>
      </div>

      <!-- Isi Konten -->
      <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Module List</h5>
          <a href="#" class="btn" style="background-color: #a18cd1; color: white;" data-bs-toggle="modal" data-bs-target="#addModuleModal">
            ADD NEW
          </a>        
        </div>

        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>ACTION</th>
              <th>MODULE NAME</th>
              <th>MODULE DESCRIPTION</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($module as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->module_name }}</td>
                <td>{{ $item->index_order }}</td>
                <!-- dan seterusnya -->
            </tr>


                <td>
                  <a href="{{ route('module.edit', $item->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('module.show', $item->id) }}" class="btn btn-info btn-sm">
                  <i class="fas fa-eye"></i>
                </a>
                
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                  <i class="fas fa-trash-alt"></i>
                </button>
                <td>{{ $item->module_name }}</td>
                <td>{{ $item->module_description }}</td>
              </tr>
            @endforeach
          </tbody>        
        </table>
      </div>

      <!-- Modal Tambah Module -->
      <div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addModuleModalLabel">Add New Module</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('module.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="mb-3">
                  <label for="module_image" class="form-label">Module Image</label>
                  <input type="file" class="form-control" id="module_image" name="module_image" accept="image/*" required>
                </div>

                <div class="mb-3">
                  <label for="module_name" class="form-label">Module Name</label>
                  <input type="text" class="form-control" id="module_name" name="module_name" required>
                </div>

                <div class="mb-3">
                  <label for="module_description" class="form-label">Module Description</label>
                  <textarea class="form-control" id="module_description" name="module_description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                  <label for="index_order" class="form-label">Index Order</label>
                  <input type="number" class="form-control" id="index_order" name="index_order" min="1" required>
                </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background-color: #a18cd1;">Save Module</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  @foreach ($module as $item => $m)
  <div class="modal fade" id="deleteModal{{ $m->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $m->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel{{ $m->id }}">Konfirmasi Penghapusan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus module ini?
        </div>
        <div class="modal-footer">
          <form action="{{ route('module.destroy', $m->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
        </div>
      </div>
    </div>
  </div>
  
  @endforeach
  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>