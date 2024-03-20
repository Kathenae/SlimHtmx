# SlimHtmx 

SlimHtmx is an experimental project aimed at integrating Slim, a PHP micro-framework, with htmx, a powerful tool for enhancing web applications with AJAX, CSS Transitions, WebSockets, and Server Sent Events directly in HTML. The goal is to simplify the backend development process by leveraging the power of htmx, making it easier to create dynamic, interactive web applications with minimal JavaScript.

### Key Features
- Dynamic Content Loading: With hx-partial, update specific sections of your page in response to HTMX requests, improving user engagement and reducing server load.

- Slim Integration: Leverage the lightweight PHP micro-framework for efficient backend development.
htmx Enhancements

- Htmx Integration: Utilize htmx to add AJAX, CSS Transitions, WebSockets, and Server Sent Events directly in HTML, enhancing your application's interactivity.

#### hx-partial
The `hx-partial` tag is used to conditionally load a part of the template based on the request's URI and method. This is useful for loading specific sections of a page in response to HTMX requests. This means that we can declare a section of the template to be partially rendered if the request matches

***Attributes***
- `route`: the uri which will render this as partial. for the partial content to be returned both the `route` and `method` should match the request and additionally the request must be an htmx request (HX-REQUEST header set).

- `method`: the http method that will be accepted, can provide many methods by separating them with the pipe `|` character

***Usage example:***
```html
<form method="post" action="/inline-validation" id="content" >
   <label for="email">Email</label>
   <input hx-post="/inline-validation?hx-validate-email" hx-target="#email-error" 
      name="email" value="<?= $email ?>"/>
      
   <div id="email-error">
      <hx-partial route="/inline-validation?hx-validate-email||/inline-validation" method="post">
         <span>
            <?= $errors['email'] ?>
         </span>
      </hx-partial>
   </div>
   <button type="Submit">
         Submit
   </button>
</form>
```

In this example we use the `hx-partial` tag to specify that when an HTMX request is made to the given route with the given method, the server should responde with the content inside that tag instead of returning the entire html content. And on the client side htmx looks at the hx-target element defined and it replaces its innerHtml with the
content received from the server.