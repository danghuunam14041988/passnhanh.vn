"""Static server for local preview; honours the PORT env var set by the preview tool."""
import functools, http.server, os, pathlib

root = pathlib.Path(__file__).resolve().parent.parent
port = int(os.environ.get("PORT", "8765"))
handler = functools.partial(http.server.SimpleHTTPRequestHandler, directory=str(root))
http.server.ThreadingHTTPServer(("127.0.0.1", port), handler).serve_forever()
