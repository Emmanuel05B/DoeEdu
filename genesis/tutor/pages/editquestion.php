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

$tutorId = $_SESSION['user_id'];

// Validate questionId
if (!isset($_GET['questionId']) || !is_numeric($_GET['questionId'])) {
    die("Invalid question ID.");
}

$questionId = intval($_GET['questionId']);
$success = false;
$error = null;

// Check if form submitted to update question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = trim($_POST['QuestionText']);
    $optionA = trim($_POST['OptionA']);
    $optionB = trim($_POST['OptionB']);
    $optionC = trim($_POST['OptionC']);
    $optionD = trim($_POST['OptionD']);
    $correctAnswer = $_POST['CorrectAnswer'];

    // Validate correct answer
    if (!in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
        $error = "Correct answer must be one of A, B, C, or D.";
    } elseif (empty($questionText) || empty($optionA) || empty($optionB) || empty($optionC) || empty($optionD)) {
        $error = "All fields are required.";
    } else {
        // Verify question belongs to tutor
        $verifyStmt = $connect->prepare("
            SELECT oa.TutorId 
            FROM onlinequestions oq
            INNER JOIN onlineactivities oa ON oq.ActivityId = oa.Id
            WHERE oq.Id = ?
        ");
        $verifyStmt->bind_param("i", $questionId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();

        if ($verifyResult->num_rows === 0) {
            die("Question not found.");
        }

        $row = $verifyResult->fetch_assoc();
        if ($row['TutorId'] != $tutorId) {
            die("You do not have permission to edit this question.");
        }
        $verifyStmt->close();

        // Update question
        $updateStmt = $connect->prepare("
            UPDATE onlinequestions
            SET QuestionText = ?, OptionA = ?, OptionB = ?, OptionC = ?, OptionD = ?, CorrectAnswer = ?
            WHERE Id = ?
        ");
        $updateStmt->bind_param("ssssssi", $questionText, $optionA, $optionB, $optionC, $optionD, $correctAnswer, $questionId);

        if ($updateStmt->execute()) {
            $success = true;
        } else {
            $error = "Failed to update the question. Please try again.";
        }
        $updateStmt->close();
    }
}

// Fetch question data for display
$stmt = $connect->prepare("
    SELECT oq.*, oa.Id AS ActivityId
    FROM onlinequestions oq
    INNER JOIN onlineactivities oa ON oq.ActivityId = oa.Id
    WHERE oq.Id = ?
");
$stmt->bind_param("i", $questionId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Question not found.");
}

$question = $result->fetch_assoc();
$stmt->close();
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
        <h1>Edit Question <small>Update question details</small></h1>
        <ol class="breadcrumb">
          <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Edit Question</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Question ID: <?php echo $question['Id']; ?></h3>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="ActivityId" value="<?php echo $question['ActivityId']; ?>">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="box-body">
                    <!-- Question -->
                    <div class="form-group">
                        <label for="QuestionText">Question</label>
                        <div class="math-box" id="question_box"><?php echo htmlspecialchars($question['QuestionText']); ?></div>
                        <input type="hidden" name="QuestionText" id="QuestionText" value="<?php echo htmlspecialchars($question['QuestionText']); ?>" class="math-latex">
                    </div>

                    <!-- Options A-D -->
                    <div class="row">
                        <?php foreach(['A','B','C','D'] as $opt): ?>
                        <div class="col-md-6 col-lg-3">
                            <label><?php echo $opt; ?>.</label>
                            <div class="math-box option-box"><?php echo htmlspecialchars($question['Option'.$opt]); ?></div>
                            <input type="hidden" name="Option<?php echo $opt; ?>" class="math-latex" value="<?php echo htmlspecialchars($question['Option'.$opt]); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Correct Answer -->
                    <div class="form-group">
                        <label for="CorrectAnswer">Correct Answer</label>
                        <select name="CorrectAnswer" id="CorrectAnswer" class="form-control" required>
                            <?php foreach(['A','B','C','D'] as $opt): ?>
                                <option value="<?php echo $opt; ?>" <?php if($question['CorrectAnswer'] === $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="box-footer text-right">
                    <a href="viewactivity.php?activityId=<?php echo $question['ActivityId']; ?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Question</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

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

var activeField = null;     // currently selected MathQuill field
var fields = [];            // store all fields (optional but useful)

// --------------------
// INIT FUNCTION
// --------------------
function initMathField(element, hiddenInputId = null) {
    var hiddenInput;

    // If ID provided → use getElementById (for question)
    if (hiddenInputId) {
        hiddenInput = document.getElementById(hiddenInputId);
    } else {
        // Otherwise → next sibling (for options)
        hiddenInput = element.nextElementSibling;
    }

    var field = MQ.MathField(element, {
        handlers: {
            edit: function () {
                if (hiddenInput) {
                    hiddenInput.value = field.latex();
                }
            }
        }
    });

    // Set initial value from DB (IMPORTANT)
    if (hiddenInput && hiddenInput.value) {
        field.latex(hiddenInput.value);
    }

    // Track active field
    element.addEventListener('click', function () {
        activeField = field;
    });

    fields.push(field);
}

// --------------------
// INITIALIZE ALL FIELDS
// --------------------
document.addEventListener('DOMContentLoaded', function () {

    // Question field
    initMathField(document.getElementById('question_box'), 'QuestionText');

    // Option fields
    document.querySelectorAll('.option-box').forEach(function (box) {
        initMathField(box);
    });

});

// --------------------
// TOOLBAR FUNCTIONS
// --------------------
function insertText(text) {
    if (activeField) {
        activeField.write(text);
        activeField.focus();
    }
}

function insertCmd(cmd) {
    if (activeField) {
        activeField.cmd(cmd);
        activeField.focus();
    }
}

// --------------------
// OPTIONAL: KEYBOARD SHORTCUTS (PRO LEVEL)
// --------------------
document.addEventListener('keydown', function(e){
    if(!activeField) return;

    // Example shortcuts
    if(e.ctrlKey && e.key === '/'){
        insertCmd('\\frac');
        e.preventDefault();
    }

    if(e.ctrlKey && e.key === 'r'){
        insertCmd('\\sqrt');
        e.preventDefault();
    }

    if(e.ctrlKey && e.key === 'p'){
        insertText('\\pi');
        e.preventDefault();
    }
});
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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Updated!',
    text: 'Question was updated successfully.',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = 'viewactivity.php?activityId=<?php echo $question['ActivityId']; ?>';
});
</script>
<?php elseif (isset($error)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '<?php echo addslashes($error); ?>',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

<style>
.math-box, .option-box { min-height: 70px; font-size: 14px; padding: 6px; }


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