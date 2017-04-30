import json
import tornado.ioloop
import tornado.web
import tornado.websocket
from tornado import options

class MainHandler(tornado.web.RequestHandler):
    @tornado.web.asynchronous
    def get(self):
        self.render("socket.html")
#         self.write('''
# <script>
# ws = new WebSocket("ws://localhost:9999/ws");
# ws.onmessage = function(e) {
#     alert('message received: ' + e.data);
# };
# </script>''')

class WebSocket(tornado.websocket.WebSocketHandler):

    waiters = set()

    def open(self):
        print("open websocket connection")
        #self.write_message("")
        self.waiters.add(self)

    def on_message(self, message):
        print(message)
        page_data = json.loads(message)
        res_data = {"data":page_data["page"]}
        res_data["status"] = "ok"
        print(res_data)
        for waiter in self.waiters:
            if waiter == self:
                waiter.write_message(json.dumps(res_data))

    def on_close(self):
        print("close websocket connection")

    def check_origin(self, origin):
        return True

app = tornado.web.Application([
    (r"/", MainHandler),
    (r"/ws", WebSocket),
])

if __name__ == "__main__":
    options.parse_command_line()
    app.listen(9999)
    tornado.ioloop.IOLoop.instance().start()
