<?php
/**
 * Utility functions for working with categories in the application
 */
require_once '../../../../connessione.php';
/**
 * Get all categories from the database
 *
 * @param mysqli $conn Database connection
 * @return array Array of category data
 */
function getAllCategories($conn) {
    $categories = [];
    
    $sql = "SELECT * FROM categoria ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    
    return $categories;
}

/**
 * Validate if a given category name exists in the database
 *
 * @param mysqli $conn Database connection
 * @param string $category_name The category name to validate
 * @return bool True if category exists, false otherwise
 */
function validateCategory($conn, $category_name) {
    $valid = false;
    
    $sql = "SELECT id FROM categoria WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $valid = true;
    }
    
    $stmt->close();
    return $valid;
}

/**
 * Get a select dropdown HTML with all categories
 *
 * @param mysqli $conn Database connection
 * @param string $selected_value (Optional) Currently selected value
 * @param string $field_name (Optional) Name attribute for the select element
 * @param bool $required (Optional) Whether the field is required
 * @return string HTML select element with category options
 */
function getCategorySelectHtml($conn, $selected_value = '', $field_name = 'tipo', $required = true) {
    $categories = getAllCategories($conn);
    $required_attr = $required ? 'required' : '';
    
    $html = '<select name="' . $field_name . '" id="' . $field_name . '" ' . $required_attr . '>';
    $html .= '<option value="" disabled' . (empty($selected_value) ? ' selected' : '') . '>TIPO DI PRODOTTO</option>';
    
    foreach ($categories as $category) {
        $selected = ($selected_value == $category['nome']) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($category['nome']) . '" ' . $selected . '>' . 
                 htmlspecialchars(ucfirst($category['nome'])) . '</option>';
    }
    
    $html .= '</select>';
    return $html;
}
?>
