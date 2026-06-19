<form action="{{ route('admin.materials.destroy', $material) }}" 
      method="POST" 
      style="display:inline;">
    @csrf
    @method('DELETE')

    <button type="submit"
            class="btn btn-danger btn-sm"
            onclick="return confirm('Yakin hapus materi ini?')">
        Hapus
    </button>
</form>