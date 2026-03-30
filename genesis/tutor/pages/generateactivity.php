<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  
?>

<!-- MathQuill CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mathquill/build/mathquill.js"></script>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Generate Quiz 2</h1>
    </section>

    <?php
        $grade = $_GET['gra'];
        $SubjectId = intval($_GET['sub']);
        $group = $_GET['group']; 

        $stmt = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
        $stmt->bind_param("i", $SubjectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $SubjectName = $row['SubjectName'];
        }
    ?>

    <section class="content">
      <div class="box box-primary">
     
        <form action="saveactivity.php" method="POST" enctype="multipart/form-data">

          <div class="box-body">
            <div class="row text-center activity-info">
              <div class="col-sm-4">
                <strong><?php echo htmlspecialchars($grade); ?></strong> 
                <input type="hidden" name="grade" value="<?php echo htmlspecialchars($grade); ?>">
              </div>
              <div class="col-sm-4">
                <strong>Subject: <?php echo htmlspecialchars($SubjectName); ?></strong> 
                <input type="hidden" name="subject" value="<?php echo $SubjectId; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Class: <?php echo htmlspecialchars($group); ?></strong> 
                <input type="hidden" name="group" value="<?php echo htmlspecialchars($group); ?>">
              </div>
            </div>

            <hr>

            <div class="form-group">
              <div class="row col-sm-6">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="chapter">Chapter Name</label>
                    <input type="text" class="form-control input-sm" id="chapter" name="chapter" placeholder="Enter chapter name" required>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="activity_title">Quiz Title</label>
                    <input type="text" class="form-control input-sm" id="activity_title" name="activity_title" placeholder="Enter activity title" required>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Attach Image (optional)</label>
                    <input type="file" name="activity_image" accept="image/*" class="form-control input-sm">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="memo_file">Upload Memo (optional)</label>
                    <input type="file" class="form-control input-sm" id="memo_file" name="memo_file" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <small class="text-muted">Allowed: PDF</small>
                  </div>
                </div> 
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea class="form-control input-sm" id="instructions" name="instructions" rows="5" placeholder="Enter activity instructions..."></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- QUESTIONS CONTAINER -->
            <div id="questions_container">
              <div class="question-block">
                <hr>
                <h4>Question 1</h4>
                <div class="form-group">
                  <label>Question</label>
                  <div class="math-box form-control" data-placeholder="Type the question here"></div>
                  <input type="hidden" name="questions[0][text]" class="math-latex">
                </div>
                <div class="row">
                  <?php $options = ['A','B','C','D']; ?>
                  <?php foreach($options as $opt): ?>
                  <div class="col-sm-3">
                    <label>Option <?php echo $opt; ?></label>
                    <div class="math-box form-control option-box"></div>
                    <input type="hidden" name="questions[0][options][<?php echo $opt; ?>]" class="math-latex">
                  </div>
                  <?php endforeach; ?>
                  <div class="col-sm-2" style="margin-top:25px;">
                    <select name="questions[0][correct]" class="form-control input-sm" required>
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="C">C</option>
                      <option value="D">D</option>
                    </select>
                  </div>
                  <div class="col-sm-2" style="margin-top:25px;">
                    <button type="button" class="btn btn-danger btn-sm remove-question-btn">Delete</button>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-default btn-sm" id="add_question_btn"><i class="fa fa-plus"></i> Add Another Question</button>

          </div>

          <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary btn-sm">Generate Activity</button>
          </div>
        </form>
      </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<!-- FIXED MATH TOOLBAR -->
<div id="math-toolbar">

  <div class="toolbar-scroll">
  
    <!-- Physics buttons -->
    <button onclick="showTab('maths-extended')">Maths+</button>
    <button onclick="showTab('physics-extended')">Physics+</button>
    <button onclick="showTab('chemistry-extended')">Chemistry+</button>
    <button onclick="showTab('close-extended')">Close</button>

    <!-- ================= Maths EXTENDED ================= -->
    <div id="maths-extended" class="tab-content" style="display:none;">
        <br>
        <button onclick="insertText('+')">+</button>
        <button onclick="insertText('-')">−</button>
        <button onclick="insertText('\\times')">×</button>
        <button onclick="insertText('\\div')">÷</button>
        <button onclick="insertText('=')">=</button>
        <button onclick="insertText('\\neq')">≠</button>
        <button onclick="insertText('<')">&lt;</button>
        <button onclick="insertText('>')">&gt;</button>
        <button onclick="insertText('\\leq')">≤</button>
        <button onclick="insertText('\\geq')">≥</button>
        <button onclick="insertText('\\approx')">≈</button>
        <button onclick="insertText('\\propto')">∝</button>
        <button onclick="insertText('\\pm')">±</button>
 
        <button onclick="insertCmd('\\frac')">a/b</button>
        <button onclick="insertCmd('^')">xⁿ</button>
        <button onclick="insertCmd('_')">xₙ</button>
        <button onclick="insertCmd('\\sqrt')">√</button>
        <button onclick="insertText('\\sqrt[3]')">∛</button>
        <button onclick="insertText('()')">( )</button>
        <button onclick="insertText('[]')">[ ]</button>
        <button onclick="insertText('{}')">{ }</button>
        <button onclick="insertText('| |')">| |</button>
        <button onclick="insertText('!')">!</button>

        <!-- Sets / Logic -->
        <button onclick="insertText('\\in')">∈</button>
        <button onclick="insertText('\\notin')">∉</button>
        <button onclick="insertText('\\subset')">⊂</button>
        <button onclick="insertText('\\subseteq')">⊆</button>
        <button onclick="insertText('\\supset')">⊃</button>
        <button onclick="insertText('\\supseteq')">⊇</button>
        <button onclick="insertText('\\cup')">∪</button>
        <button onclick="insertText('\\cap')">∩</button>
        <button onclick="insertText('\\oplus')">⊕</button>
        <button onclick="insertText('\\forall')">∀</button>
        <button onclick="insertText('\\exists')">∃</button>
        <button onclick="insertText('\\neg')">¬</button>
        <button onclick="insertText('\\wedge')">∧</button>
        <button onclick="insertText('\\vee')">∨</button>

        <!-- Trigonometry & Calculus-->
        <button onclick="insertText('\\sin')">sin</button>
        <button onclick="insertText('\\cos')">cos</button>
        <button onclick="insertText('\\tan')">tan</button>
        <button onclick="insertText('\\cot')">cot</button>
        <button onclick="insertText('\\sec')">sec</button>
        <button onclick="insertText('\\csc')">csc</button>
        <button onclick="insertText('\\theta')">θ</button>
        <button onclick="insertText('\\alpha')">α</button>
        <button onclick="insertText('\\beta')">β</button>
        <button onclick="insertText('\\gamma')">γ</button>

        <button onclick="insertText('\\int_{}^{})')">∫_{}^{} </button>
        <button onclick="insertText('\\int_0^\\infty')">∫₀∞</button>
        <button onclick="insertText('\\sum_{}^{})')">Σ_{}^{} </button>
        <button onclick="insertText('d/dx')">d/dx</button>
        <button onclick="insertText('\\partial')">∂</button>
        <button onclick="insertText('\\partial^2')">∂²</button>
        <button onclick="insertText('\\lim')">lim</button>
  
        <!-- Greek letters -->
        <button onclick="insertText('\\alpha')">α</button>
        <button onclick="insertText('\\beta')">β</button>
        <button onclick="insertText('\\gamma')">γ</button>
        <button onclick="insertText('\\delta')">δ</button>
        <button onclick="insertText('\\epsilon')">ε</button>
        <button onclick="insertText('\\zeta')">ζ</button>
        <button onclick="insertText('\\eta')">η</button>
        <button onclick="insertText('\\theta')">θ</button>
        <button onclick="insertText('\\iota')">ι</button>
        <button onclick="insertText('\\kappa')">κ</button>
        <button onclick="insertText('\\lambda')">λ</button>
        <button onclick="insertText('\\mu')">μ</button>
        <button onclick="insertText('\\nu')">ν</button>
        <button onclick="insertText('\\xi')">ξ</button>
        <button onclick="insertText('\\pi')">π</button>
        <button onclick="insertText('\\rho')">ρ</button>
        <button onclick="insertText('\\sigma')">σ</button>
        <button onclick="insertText('\\tau')">τ</button>
        <button onclick="insertText('\\upsilon')">υ</button>
        <button onclick="insertText('\\phi')">φ</button>
        <button onclick="insertText('\\chi')">χ</button>
        <button onclick="insertText('\\psi')">ψ</button>
        <button onclick="insertText('\\omega')">ω</button>
        <button onclick="insertText('\\Gamma')">Γ</button>
        <button onclick="insertText('\\Delta')">Δ</button>
        <button onclick="insertText('\\Theta')">Θ</button>
        <button onclick="insertText('\\Lambda')">Λ</button>
        <button onclick="insertText('\\Xi')">Ξ</button>
        <button onclick="insertText('\\Pi')">Π</button>
        <button onclick="insertText('\\Sigma')">Σ</button>
        <button onclick="insertText('\\Upsilon')">Υ</button>
        <button onclick="insertText('\\Phi')">Φ</button>
        <button onclick="insertText('\\Psi')">Ψ</button>
        <button onclick="insertText('\\Omega')">Ω</button>
        
    </div>

    <!-- ================= PHYSICS EXTENDED ================= -->
    <div id="physics-extended" class="tab-content" style="display:none;">
        <br>
        <button onclick="insertText('F=ma')">F=ma</button>
        <button onclick="insertText('p=mv')">p=mv</button>
        <button onclick="insertText('KE=\\frac{1}{2}mv^2')">KE</button>
        <button onclick="insertText('PE=mgh')">PE</button>
        <button onclick="insertText('W=Fd')">W</button>
        <button onclick="insertText('P=\\frac{W}{t}')">Power</button>
        <button onclick="insertText('v=u+at')">v=u+at</button>
        <button onclick="insertText('s=ut+\\frac{1}{2}at^2')">s</button>
        <button onclick="insertText('F=G\\frac{m_1 m_2}{r^2}')">Gravity</button>

        <button onclick="insertText('F=ma')">F=ma</button>
            <button onclick="insertText('v=u+at')">v=u+at</button>
            <button onclick="insertText('s=ut+\\frac{1}{2}at^2')">s=ut+½at²</button>
            <button onclick="insertText('p=mv')">p=mv</button>
            <button onclick="insertText('KE=\\frac{1}{2}mv^2')">KE</button>
            <button onclick="insertText('PE=mgh')">PE</button>
            <button onclick="insertText('V=IR')">V=IR</button>
            <button onclick="insertText('P=VI')">P=VI</button>
            <button onclick="insertText('E=mc^2')">E=mc²</button>
            <button onclick="insertText('F=G\\frac{m_1m_2}{r^2}')">Gravity</button>

        <button onclick="insertText('V=IR')">V=IR</button>
        <button onclick="insertText('Q=It')">Q=It</button>
        <button onclick="insertText('P=VI')">P=VI</button>
        <button onclick="insertText('F=q(E+v\\times B)')">Lorentz</button>
        <button onclick="insertText('U=QV')">Energy</button>
        <button onclick="insertText('C=\\frac{Q}{V}')">Capacitance</button>
        <button onclick="insertText('B=\\frac{\\mu_0 I}{2\\pi r}')">Magnetic Field</button>

        <button onclick="insertText('n=c/v')">n=c/v</button>
        <button onclick="insertText('f=\\frac{1}{T}')">Frequency</button>
        <button onclick="insertText('\\lambda=v/f')">Wavelength</button>
        <button onclick="insertText('1/f=1/u+1/v')">Lens/Mirror</button>

        <button onclick="insertText('PV=nRT')">PV=nRT</button>
        <button onclick="insertText('Q=mc\\Delta T')">Heat</button>
        <button onclick="insertText('W=P\\Delta V')">Work</button>

        <button onclick="insertText('E=mc^2')">Relativity</button>
        <button onclick="insertText('\\lambda=h/p')">de Broglie</button>
        <button onclick="insertText('f=E/h')">Photon f</button>
        <button onclick="insertText('R=1/\\lambda=R_H(1/n_1^2-1/n_2^2)')">Rydberg</button>
    </div>

    <!-- ================= CHEMISTRY ================= -->
    <div id="chemistry-extended" class="tab-content" style="display:none;">
        <br>
        <button onclick="insertText('PV=nRT')">PV=nRT</button>
        <button onclick="insertText('c=n/V')">Concentration</button>
        <button onclick="insertText('n=m/M')">Moles</button>
        <button onclick="insertText('pH=-\\log[H^+]')">pH</button>
        <button onclick="insertText('Q=mc\\Delta T')">Heat</button>
        <button onclick="insertText('\\Delta H=H_{products}-H_{reactants}')">ΔH</button>
        <button onclick="insertText('E=hv')">Photon Energy</button>
        <button onclick="insertText('K_c=[products]/[reactants]')">Kc</button>
        <button onclick="insertText('ΔG=ΔH-TΔS')">ΔG</button>
        <button onclick="insertText('a/b=stoichiometry')">Stoichiometry</button>
    </div>

    <!-- ================= close ================= -->
    <div id="close-extended" class="tab-content" style="display:none;">
                
    </div>

  </div>
</div>

<!-- MathQuill JS -->
<script>

var MQ = MathQuill.getInterface(2);
var activeField = null;
var questionIndex = 1; // first question exists

function initMathFieldBlock(block) {
    // Question
    var mathBox = block.querySelector('.math-box:not(.option-box)');
    var hiddenInput = block.querySelector('.math-latex:not(.option-box)');
    var qField = MQ.MathField(mathBox, {
        handlers: { edit: function(){ hiddenInput.value = qField.latex(); } }
    });
    mathBox.addEventListener('click', function(){ activeField = qField; });

    // Options
    block.querySelectorAll('.option-box').forEach(function(optBox){
        var optHidden = optBox.nextElementSibling;
        var optField = MQ.MathField(optBox, {
            handlers: { edit: function(){ optHidden.value = optField.latex(); } }
        });
        optBox.addEventListener('click', function(){ activeField = optField; });
    });
}

// Initialize existing blocks
document.querySelectorAll('.question-block').forEach(initMathFieldBlock);

// Add question button
document.getElementById('add_question_btn').addEventListener('click', function () {
    const container = document.getElementById('questions_container');
    const template = document.querySelector('.question-block');
    const clone = template.cloneNode(true);

    // Reset values
    clone.querySelectorAll('.math-box').forEach(box=>box.innerHTML='');
    clone.querySelectorAll('.math-latex').forEach(input=>input.value='');
    clone.querySelectorAll('select').forEach(sel=>sel.value='A');
    clone.querySelectorAll('textarea').forEach(txt=>txt.value='');

    clone.querySelector('h4').textContent = `Question ${questionIndex + 1}`;
    clone.querySelectorAll('input, select, textarea').forEach(el=>{
        if(el.name) el.name = el.name.replace(/\[\d+\]/, `[${questionIndex}]`);
    });

    container.appendChild(clone);

    // Initialize MathQuill for the new block
    initMathFieldBlock(clone);

    questionIndex++;
});

// Delete question
document.getElementById('questions_container').addEventListener('click', function(e){
    if(e.target.closest('.remove-question-btn')){
        const block = e.target.closest('.question-block');
        if(document.querySelectorAll('.question-block').length > 1){
            block.remove();
        }
    }
});

// Toolbar functions
function insertText(text){ if(activeField){ activeField.write(text); activeField.focus(); } }
function insertCmd(cmd){ if(activeField){ activeField.cmd(cmd); activeField.focus(); } }

</script>
<script>
function showTab(tabId) {
    // hide all tab contents
    var tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(t => t.style.display = 'none');

    // show the selected tab
    var tab = document.getElementById(tabId);
    if(tab) tab.style.display = 'block';
}
</script>

<style>
.form-control { max-width: 100%; }
.question-block { margin-bottom: 20px; }
.box-header.text-center h3 { text-align: center; margin: 0 auto; font-weight: 600; }
.activity-info { margin-top: 15px; margin-bottom: 15px; font-size: 16px; }
.activity-info strong { display: block; margin-bottom: 5px; }
@media (max-width: 768px) {
    .form-control { font-size: 14px; }
    h4 { font-size: 16px; }
    .btn { font-size: 14px; padding: 6px 10px; }
    .activity-info { font-size: 14px; }
}

.math-box {
    min-height: 70px;
    font-size: 14px;
    padding: 6px;
}
.option-box {
    min-height: 70px;
    font-size: 14px;
    padding: 6px;
}

#math-toolbar {
    position: fixed; 
    top: 55px; 
    right: 30px; 
    width: 90%;          /* use percentage for responsiveness */
    max-width: 900px;    /* keeps it from growing too big on large screens */
    max-height: 60vh;    /* vertical limit */
    overflow: auto;      /* both horizontal & vertical scroll if needed */
    background: #f9f9f9; 
    border: 1px solid #132ea7; 
    padding: 10px; 
    border-radius: 5px; 
    z-index: 9999;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

@media (max-width: 400px) {
    #math-toolbar {
        right: 10px;
        top: 55px;
        width: 50%;
        font-size: 12px;  /* shrink buttons slightly */
    }
    #math-toolbar button {
        padding: 4px 6px;
        font-size: 12px;
    }
}


</style>

</body>
</html>