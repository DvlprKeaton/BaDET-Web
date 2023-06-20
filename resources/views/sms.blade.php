<form method="POST" action="/send-sms">
    @csrf
    <label for="recipient">Recipient:</label>
    <input type="text" name="recipient" id="recipient">
    <br>
    <label for="message">Message:</label>
    <textarea name="message" id="message"></textarea>
    <br>
    <button type="submit">Send SMS</button>
</form>
