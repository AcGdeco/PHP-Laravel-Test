<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:6',
            'contact' => 'required|digits:9|unique:contacts,contact',
            'email' => 'required|email|unique:contacts,email',
        ]);

        try {
            Contact::create($validated);

            return redirect()->route('contacts.index')->with('success', 'Contato adicionado com sucesso!');
        } catch (QueryException $e) {
            // MySQL duplicate entry code is 1062, SQLSTATE 23000
            $errorField = null;
            $message = 'Erro ao salvar contato.';

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'contacts_contact_unique') || str_contains($msg, "'contact'") || str_contains($msg, 'contact')) {
                    $errorField = 'contact';
                    $message = 'Este contato já existe.';
                } elseif (str_contains($msg, 'contacts_email_unique') || str_contains($msg, "'email'") || str_contains($msg, 'email')) {
                    $errorField = 'email';
                    $message = 'Este email já está em uso.';
                } else {
                    $message = 'Contato ou email já cadastrado.';
                }
            }

            if ($errorField) {
                return back()->withInput()->withErrors([$errorField => $message]);
            }

            return back()->withInput()->withErrors(['error' => $message]);
        }
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:6',
            'contact' => 'required|digits:9|unique:contacts,contact,' . $contact->id,
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
        ]);

        try {
            $contact->update($validated);

            return redirect()->route('contacts.show', $contact)->with('success', 'Contato atualizado com sucesso!');
        } catch (QueryException $e) {
            $errorField = null;
            $message = 'Erro ao atualizar contato.';

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'contacts_contact_unique') || str_contains($msg, "'contact'") || str_contains($msg, 'contact')) {
                    $errorField = 'contact';
                    $message = 'Este contato já existe.';
                } elseif (str_contains($msg, 'contacts_email_unique') || str_contains($msg, "'email'") || str_contains($msg, 'email')) {
                    $errorField = 'email';
                    $message = 'Este email já está em uso.';
                } else {
                    $message = 'Contato ou email já cadastrado.';
                }
            }

            if ($errorField) {
                return back()->withInput()->withErrors([$errorField => $message]);
            }

            return back()->withInput()->withErrors(['error' => $message]);
        }
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contato deletado com sucesso!');
    }
}
