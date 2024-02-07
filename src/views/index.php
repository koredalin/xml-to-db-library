<?php require_once  'layouts/header.php'; ?>

<div class="default-container">
    <h1><?php echo $title; ?></h1>

    <button id="parse_xml_btn">Parse XML input</button>
</div>

<!-- XML input text modal window. -->
<div id="xml_text_modal" class="modal">
    <pre>
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="xml_text"></div>
        </div>
    </pre>
</div>

<script src="js/parse_xml.js"></script>

<?php require_once 'layouts/footer.php';
