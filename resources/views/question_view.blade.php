
<!doctype html>
<html lang="kr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>QnA</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style type="text/css">
      #main{
        width:100%;
        min-width:400px;
        margin:auto;
        margin-top:50px;
      }

      #div_regist{
        width:100%;
        max-width:800px;
        margin:auto;
      }

      #div_list{
        width:100%;
        max-width:800px;
        margin:auto;
        margin-top:50px;
      }
      #btn_regist{
        margin-top:30px;
      }
      input{
        margin-top:30px;
      }
      #answer{
          width:80%;
          margin-left:10%;
      }
      .card{
          margin-bottom:20px;
          width:80%;
          margin-left:10%;
          text-align:left;
          cursor:pointer;
      }
    </style>
  </head>
  
  <script src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
  <script src="js/jquery.cookie.js"></script>

  <script>
    /*$().ready(function(){


      $("#btn_regist").click(function(){
        const question = $("#question").val();
        
        $.ajax({
          method: "POST",
          url: "/regist_question",
          data: { question : question, user_id : user_id}
        })
        .done(function(data) {
            const status = data.status;
            alert(data.status);
            /*if(status == "200"){ 
              $.cookie('token', data.token, { path: '/' });
              window.location.href="reservation_cs.html";
            }else{
              alert("계정정보를 확인해 주세요.");
            }
        });
      });
    });*/

    function go_view(id){
        location.href="/view_question?question_id="+id;
    }

  </script>

  <body class="text-center">
    <div id="main">
        <div id="div_regist">
            <form class="form-signin" method="POST" action="{{ route('regist_answer') }}">
            @csrf
                <h1 class="h3 mb-3 font-weight-normal">{{ $list->question->question }}</h1>
                <input type="text" name="answer" id="answer" class="form-control" placeholder="자유롭게 답변해 주세요." required autofocus>
                <input type="hidden" name="question_id" value="{{ $list->question->id }}"/>
                <button class="btn btn-primary btn-block" id="btn_regist"  type="submit">등록</button>
            </form>  
        </div>
        <div id="div_list">
        @forelse($list->answers as $answer)
            <div class="card">
                
                <div class="card-body">
                    {{ $answer['answer'] }}
                </div> 
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    답변이 없습니다.
                </div>
            </div>
        @endforelse 
        <div>
    </div>
    
  </body>
</html>