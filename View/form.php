
<form method = "POST" enctype="multipart/form-data">
    <fieldset>
        <table>
            <tbody>
                <tr>
                    <th>{$DATA.TITLE}</th>
                    <td><input name = "title" type = "text" placeholder = "{$DATA.TEXT}"></td>
                </tr>
                <tr>
                    <th>{$DATA.DATE}</th>
                    <td><input name = "date" type = "date" placeholder = "{$DATA.DATE}"></td>
                </tr>
                <tr>
                    <th>{$DATA.IMG}</th>
                    <td><input name="image" type="file"></td>
                </tr>
                <tr>
                    <th colspan = "2">{$DATA.CONTENT}</th>
                </tr>
                <tr>
                    <td colspan = "2">
                        <textarea name = "content" placeholder = "{$DATA.CONTENT}"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan = "2"><input type = "submit" value = "${DATA.SUBMIT}"></td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</form>
