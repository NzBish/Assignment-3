Specifications 
1.Login Page: This is the first screen that should open. Application should only give access to logged in users. Access to any ‘internal’ page without authorised session should result in a redirect to this page. Done(Chris)
 
Correct username/password should open Welcome page. 
 
2.Register Page: Registration form should have the following fields:
 • Name • Username • E-mail address • Password • Repeated password 
 You must implement client-side validation that includes at least the following:
  1. Username must only contain alphanumeric characters  
  2. Username must not already exist (check asynchronously using AJAX) 
  3. Password must be between 7 and 15 alphanumeric characters and contain at least one upper case letter (no special characters allowed) 
  4. Password and repeated password must match 

3.Welcome Page: A short welcome message (including user’s name) and usage instructions. 
 
4.Search Page: A search dialog where the user can enter a query.  The query should be matched to product names and all products with a full or partial match should be displayed. If no matches found, display an appropriate message. 
 
The search should be dynamic and bring up the results as the user types the query (use AJAX to query the server asynchronously). 
 
5.Browse Page: This part is optional. This is a common feature in web applications. Complete it if you want additional practice or experience. 
 
Browse page should be split into 2 panes: filter sidebar on the left and results pane to the right. Filter sidebar should contain an ‘in-stock only’ filter that limits the displayed products to ones with stock quantity > 0. Under it, place category filters. This list shoud be produced dynamically from the existing categories. The results pane should display all products in the selected categories. 
 
As the number of displayed products can be quite large and require the page to be scrolled, the left sidebar should ‘float’. i.e. stay in constant position in the window as the page is scrolled down. 
 
6.Top Menu: Welcome, Search and Browse pages should include a menu at the top, containing company logo (linked to the Welcome page), Browse, Search and Logout links. The menu item should light up when a mouse is hovered over it. Done except for logo(Chris)
 
7.Products: Products should include the following information: Stock Keeping Unit (SKU), Name, Cost, Category, Stock Quantity. Done(Chris)
 
8.Presentation Front-end HTML must be styled using CSS. You are free to choose if you want to work with vanilla CSS or SCSS. If you choose SCSS, please submit SCSS files along with compiled CSS. Done using CSS(Chris)