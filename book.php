<!DOCTYPE html>
<html>
  <head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="../Signin/bootstrap.css" />
  </head>
  <body>
    <div class="container">
      <div class="row col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
          <div class="panel-heading text-center">
            <h1>Adding a book</h1>
          </div>
          <div class="panel-body">
            <form action="addbook.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="title">TITLE</label>
                <input
                  type="text"
                  class="form-control"
                  id="title"
                  name="title"
                />
              </div>
              <div class="form-group">
                <label for="author">author</label>
                <input
                  type="text"
                  class="form-control"
                  id="author"
                  name="author"
                />
              </div>
              
              <div class="form-group">
                <label for="genre">genre</label>
                <input
                  type="text"
                  class="form-control"
                  id="genre"
                  name="genre"
                />
              </div>
              <div class="form-group">
                <label for="year">Year Published</label>
                <input
                  type="year"
                  class="form-control"
                  id="year"
                  name="year"
                />
              </div>
              <div class="form-group">
                <label for="language">language</label>
                <input
                  type="language"
                  class="form-control"
                  id="language"
                  name="language"
                />
                <div class="form-group">
              <label for="image">Book Cover Image</label>
              <input type="file" class="form-control" id="image" name="picture" accept="image/*" />
                  </div>


              </div>
              <input type="submit" class="btn btn-primary" />
            </form>
          </div>
          <div class="panel-footer text-right">
            <small>&copy; Technical Babaji</small>
          </div>
        </div>
      </div>
    </div>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <img src="..." class="rounded mr-2" alt="...">
    <strong class="mr-auto">Bootstrap</strong>
    <small>11 mins ago</small>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="toast-body">
    Hello, world! This is a toast message.
  </div>
</div>
  </body>
</html>


    