
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $mensajes = DB::connection('mongodb')
            ->collection('mensajes')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json($mensajes);
    }

    public function store(Request $request)
    {
        $mensaje = [
            'usuario_id' => auth()->id(),
            'nombre'     => auth()->user()->name,
            'contenido'  => $request->input('mensaje'),
            'created_at' => now(),
        ];

        DB::connection('mongodb')->collection('mensajes')->insert($mensaje);
        return response()->json(['status' => 'ok']);
    }

    public function destroy($id)
    {
        $mensaje = DB::connection('mongodb')->collection('mensajes')->where('_id', $id)->first();

        if ($mensaje && $mensaje['usuario_id'] == auth()->id()) {
            DB::connection('mongodb')->collection('mensajes')->where('_id', $id)->delete();
            return response()->json(['status' => 'eliminado']);
        }

        return response()->json(['status' => 'no permitido'], 403);
    }
}
