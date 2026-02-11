import re

file_path = "templates/admin_users_view.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Truncate at the junk start
# The junk starts with "                        <tr>" after the </div>
# We can find "</div>                        <tr>" or just look for the last </div> of the good block
# The good block ends with </div> which closes .animate-fade-in
# But there are nested divs.
# Let's search for the specific transition string we saw: "</div>                        <tr>"
junk_start_marker = "</div>                        <tr>"
if junk_start_marker in content:
    kept_content = content.split(junk_start_marker)[0] + "</div>"
else:
    # Fallback if whitespace differs: find "<tr>" after "Manage Users"
    # This is risky. Let's try to find the last </table> and the following </div> </div> </div>
    # The file structure is:
    # <div class="animate-fade-in"> ... <div class="card"> ... <div class="table-responsive"> ... <table>...</table> </div> </div> </div>
    # So we want to keep up to the 3rd closing div after </table>
    pattern = r"(</table>\s*</div>\s*</div>\s*</div>)"
    match = re.search(pattern, content, re.DOTALL)
    if match:
        kept_content = content[:match.end()]
    else:
        print("Could not find cut point")
        exit(1)

# 2. Replace the Edit Link with Edit Button + Delete Form
old_edit_link_pattern = r'<a href="users\.php\?edit_id=<\?= \$u\[\'id\'\] \?>" class="btn btn-primary btn-sm">\s*✏️ Modifier\s*</a>'

new_edit_buttons = """
                                <button class="btn btn-primary btn-sm" onclick='openEditModal(<?= json_encode($u, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                    ✏️ Modifier
                                </button>
                                <form method="POST" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">🗑️</button>
                                </form>
"""

kept_content = re.sub(old_edit_link_pattern, new_edit_buttons.strip(), kept_content, flags=re.DOTALL)

# 3. Append Modal
modal_code = """

<!-- Modal de modification -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2 style="margin-bottom: 2rem;">✏️ Modifier l'utilisateur</h2>
        
        <form method="POST" id="editForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" id="edit_user_id">
            
            <div class="form-group">
                <label for="edit_email">📧 Adresse Email</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="edit_pseudo">👤 Pseudo</label>
                <input type="text" id="edit_pseudo" name="pseudo" required maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="edit_score">🏆 Score Total</label>
                <input type="number" id="edit_score" name="score" min="0" max="999999" required>
            </div>
            
            <div class="form-group" style="background: var(--bg-input); padding: 1rem; border-radius: var(--radius-sm); border-left: 4px solid var(--primary);">
                <label style="display: flex; align-items: center; cursor: pointer; margin: 0; gap: 0.5rem; color: var(--text-main);">
                    <input type="checkbox" id="edit_is_admin" name="is_admin" value="1" style="width: auto;">
                    <span>🛡️ <strong>Administrateur</strong></span>
                </label>
            </div>

            <div class="text-right" style="margin-top: 2rem;">
                <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_pseudo').value = user.pseudo || user.username || '';
    document.getElementById('edit_score').value = user.score || 0;
    document.getElementById('edit_is_admin').checked = user.is_admin == 1;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeEditModal();
    }
}
</script>
"""

final_content = kept_content + modal_code

with open(file_path, "w", encoding="utf-8") as f:
    f.write(final_content)

print("File updated successfully")
