<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Quiz Generator with MathQuill</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.js"></script>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    h2 { margin-bottom: 20px; }
    .question-block { margin-bottom: 20px; border:1px solid #ddd; padding:15px; border-radius:5px; }
    .math-box { border:1px solid #ccc; padding:10px; min-height:40px; cursor:text; margin-bottom:10px; }
    .remove-question-btn { margin-top:5px; }
    .row { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .row > div { flex:1; }

    /* Fixed Side Toolbar */
    #math-toolbar {
      position: fixed;
      top: 50px;
      right: 20px;
      width: 220px;
      max-height: 90vh;
      overflow-y: auto;
      background: #f9f9f9;
      border: 1px solid #ccc;
      padding: 10px;
      z-index: 9999;
      border-radius: 5px;
    }
    #math-toolbar .group button {
      padding:4px 6px; margin:2px; cursor:pointer; font-size:12px;
    }
    #add_question_btn, button[type=submit] {
      padding: 8px 12px; margin:5px 0; cursor:pointer;
    }
  </style>
</head>
<body>

<h2>Quiz Generator (Math Enabled)</h2>

<form action="saveactivity.php" method="POST">

  <div id="questions_container">

    <!-- QUESTION TEMPLATE -->
    <div class="question-block">
      <h4>Question 1</h4>

      <div class="form-group">
        <label>Question (Math Enabled)</label>
        <div class="math-box"></div>
        <input type="hidden" class="math-latex" name="questions[0][text]">
      </div>

      <div class="row">
        <div><input type="text" name="questions[0][options][A]" placeholder="Option A" required></div>
        <div><input type="text" name="questions[0][options][B]" placeholder="Option B" required></div>
        <div><input type="text" name="questions[0][options][C]" placeholder="Option C" required></div>
        <div><input type="text" name="questions[0][options][D]" placeholder="Option D" required></div>
        <div>
          <select name="questions[0][correct]" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
        </div>
        <div>
          <button type="button" class="remove-question-btn">Delete</button>
        </div>
      </div>

    </div>
    <!-- END QUESTION TEMPLATE -->

  </div>

  <button type="button" id="add_question_btn">Add Another Question</button>
  <button type="submit">Generate Quiz</button>
</form>

<!-- Fixed Math Toolbar -->
<div id="math-toolbar">
  <div class="group">
    <b>Basic Math</b>
    <button onclick="insertText('+')">+</button>
    <button onclick="insertText('-')">−</button>
    <button onclick="insertText('\\times')">×</button>
    <button onclick="insertText('\\div')">÷</button>
    <button onclick="insertText('=')">=</button>
    <button onclick="insertCmd('^')">xⁿ</button>
    <button onclick="insertCmd('_')">xₙ</button>
    <button onclick="insertCmd('\\frac')">a/b</button>
    <button onclick="insertCmd('\\sqrt')">√</button>
  </div>
  <div class="group">
    <b>Greek</b>
    <button onclick="insertText('\\alpha')">α</button>
    <button onclick="insertText('\\beta')">β</button>
    <button onclick="insertText('\\gamma')">γ</button>
    <button onclick="insertText('\\theta')">θ</button>
  </div>
  <div class="group">
    <b>Calculus</b>
    <button onclick="insertText('\\int')">∫</button>
    <button onclick="insertText('\\sum')">Σ</button>
    <button onclick="insertText('d/dx')">d/dx</button>
    <button onclick="insertText('\\partial')">∂</button>
    <button onclick="insertText('\\lim')">lim</button>
  </div>
</div>

<script>
  var MQ = MathQuill.getInterface(2);
  var activeField = null;
  var questionIndex = 1; // Already have 1

  // Initialize MathQuill fields
  function initMathFields() {
    $('.question-block').each(function(){
      if (!$(this).data('mathquill-initialized')) {
        var mathBox = $(this).find('.math-box')[0];
        var hiddenInput = $(this).find('.math-latex')[0];
        var field = MQ.MathField(mathBox, {
          handlers: {
            edit: function() {
              hiddenInput.value = field.latex();
            }
          }
        });
        $(mathBox).on('click', function(){ activeField = field; });
        $(this).data('mathquill-initialized', true);
      }
    });
  }

  // Add new question dynamically
  $('#add_question_btn').on('click', function(){
    var container = $('#questions_container');
    var newQ = $('.question-block').first().clone();
    newQ.find('input').val('');
    newQ.find('select').val('A');
    newQ.find('.math-box').text('');
    newQ.find('.math-latex').attr('name', `questions[${questionIndex}][text]`);
    newQ.find('h4').text(`Question ${questionIndex+1}`);
    container.append(newQ);
    questionIndex++;
    initMathFields();
  });

  // Remove question
  $('#questions_container').on('click', '.remove-question-btn', function(){
    if($('.question-block').length > 1){
      $(this).closest('.question-block').remove();
    }
  });

  // Toolbar insertion
  function insertText(text) { if(activeField){ activeField.write(text); activeField.focus(); } }
  function insertCmd(cmd) { if(activeField){ activeField.cmd(cmd); activeField.focus(); } }

  // Initialize first question
  initMathFields();
</script>

</body>
</html>