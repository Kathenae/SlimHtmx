# SlimHtmx 

Experimenting with combining Slim and htmx with some custom template tags and expressions that make it super easy to handle htmx requests. The idea is to enhance the php backend in a way that makes it easy to to leverage the power of htmx

#### hx-partial
The `hx-partial` tag is used to conditionally load a part of the template based on the request's URI and method. This is useful for loading specific sections of a page in response to HTMX requests. This means that we can declare a section of the template to be partially rendered if the request matches

***Attributes***
- `route`: the uri which will render this as partial. for the partial content to be returned both the `route` and `method` should match the request and additionally the request must be an htmx request (HX-REQUEST header set).

- `method`: the http method that will be accepted, can provide many methods by separating them with the pipe `|` character

- `hx-only`: By default if the request is not an htmx request the contents of the partial will still be included in the final html response. setting this to true ensures the content is only rendered when its a matching htmx request

***Usage example:***
```html
<div>
   <label for="email">Email</label>
   <input hx-post="/sign-in?hx-validate-email" hx-target="#email-error" class="control w-full" type="email"
      name="email" id="email" value="{{ $email }}" />
   <hx-partial route="/sign-in?hx-validate-email" method="post">
      <div id="email-error">
         <span class="text-red-600 text-sm">
            {{ $errors['email'] }}
         </span>
      </div>
   </hx-partial>
</div>
```

In this example we use the `hx-partial` tag to specify that when an HTMX request is made to the given route with the given method, the server should responde with the content inside that tag instead of returning the entire html content. And on the client side htmx looks at the hx-target element defined and it replaces its innerHtml with the
content received from the server.