<form action="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" method="post">
    <fieldset class="form-group">
        <label class="form-control-label" for="exampleInput1">Type your message</label>
        <textarea required name="comment" class="form-control" id="comment" cols="30" rows="10"></textarea>
    </fieldset>
    <br>
    <input type="submit" class=" btn btn-primary-outline" value="Submit">

</form>
