{if $messageResult  =='true' }
    <div class="alert alert-success" role="alert">
        <p class="alert-text">Thank you for your review </p>
    </div>
{elseif $messageResult == "false"}
    Sometghin went wrong herer!!!!
{/if}

{if $comments}
<h2>
Comments
</h2>
    {foreach from=$comments item=element}
         <div class=" row" >
               <div class="col-12" >
               <div class="comment">
                    <div class="comment-avatar" >
                        
                    </div> 
                    <div class="comment-content" >
                        <div class="comment-author" >
                            <a href="mailto:{$element.email}">{$element.firstname}</a>
                        </div>
                        {$element.comment}  
                    </div> 
               </div> 
            </div> 
         </div>
    {/foreach}
{/if}



<form action="" method="post">
    <fieldset class="form-group">
        <label class="form-control-label" for="exampleInput1">Type your message</label>
        <textarea required name="comment" class="form-control" id="comment" cols="30" rows="10"></textarea>
    </fieldset>
    <br>
    <input type="submit" class=" btn btn-primary-outline" value="Submit">

</form>
