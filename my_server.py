import tornado.ioloop
import tornado.web
import tornado.websocket
from tornado import options

class MainHandler(tornado.web.RequestHandler):
    @tornado.web.asynchronous
    def get(self):
        self.render("index.html")

class WebSocket(tornado.websocket.WebSocketHandler):
    def open(self):
        print("open websocket connection")
        self.write_message("The server says: 'Hello'. Connection was accepted.")

    def on_message(self, message):
        print(message)

    def on_close(self):
        print("close websocket connection")

app = tornado.web.Application([
    (r"/", MainHandler),
    (r"/ws", WebSocket),
])

if __name__ == "__main__":
    options.parse_command_line()
    app.listen(9999)
    tornado.ioloop.IOLoop.instance().start()
