# Cardtraders

I delivered a working prototype of the web application where a user can register and log in, buy a sample gift card and receive it by email and sell a sample gift card. 

The limitations of the prototype application are that the available gift cards are not real, selling a gift card will not result in receiving money and only one payment method is available (PayPal).

On security, I tried to protect the web application against the most critical security risks to web applications as mentioned by the Open Web Application Security Project (2017). These include injection attacks, sensitive data exposure, security misconfiguration, cross-site scripting flaws,  and man-in-the-middle attacks. I did this by implementing validation and cleaning of all user input, using prepared SQL statements (W3schools, n.d.), hashing passwords (PHP, n.d.), encrypting gift card codes (W3docs, n.d.), and implementing HTTPS (Medic, 2020). 
