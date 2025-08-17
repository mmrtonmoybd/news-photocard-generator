<form action="/test" method="post" enctype="multipart/form-data">
  @csrf
  <input type="file" name="photo" accept="image/*" required>
  <button type="submit">Upload & Generate</button>
</form>
