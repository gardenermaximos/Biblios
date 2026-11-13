# BibliOS

BibliOS is an academic web application developed for managing a school library. The system handles book inventory, reader records, loans, and returns, with an interface designed around accessibility—using enlarged typography, high contrast, and straightforward layouts to facilitate reading and navigation.

## Technologies Used

* **Backend:** PHP (with PDO for database operations and session-based authentication)
* **Database:** MySQL (managed via phpMyAdmin)
* **Frontend:** HTML5 and responsive CSS3
* **Icons:** Font Awesome

## Main Features

* **Authentication:** Login and logout system with session verification across all internal pages.
* **Dashboard:** Real-time summary displaying counts of active readers, local physical books, active loans, overdue returns, and the general catalog.
* **Reader Management:** Register, edit, search, filter (active/inactive), and remove readers (students, teachers, staff, and community members).
* **Book Catalog and Local Collection:** Manage books belonging to the local physical collection as well as titles available through partner libraries, including category classification and availability status.
* **Loans and Returns:** Register book loans with an automatic 14-day return period, track ongoing and overdue loans, edit loan records, and register book returns with condition notes.

## Project Structure

The project is organized into modular folders corresponding to each screen and function of the system:

* `pagina_login1/` — User authentication (`index.php`) and session termination (`logout.php`).
* `pagina_inicial2/` — Main dashboard (`home.php`) with real-time indicators and navigation cards.
* `pagina_leitores3/` — Reader listing, search, and status filtering (`leitores.php`).
* `pagina_livros4/` — Local physical book inventory (`livros_cadastrados.php`).
* `pagina_emprestimos5/` — Loan listing (`emprestimos.php`), quick return completion (`concluir_emprestimo.php`), and loan editing (`editar_emprestimo.php`).
* `pagina_devolucoes6/` — Overdue loan tracking (`devolucoes_atrasadas.php`).
* `pagina_gerenciar_leitores7/` — Reader registration (`gerenciar_leitores.php`), editing (`editar_leitor.php`), and deletion (`excluir_leitor.php`).
* `pagina_catalogo8/` — General and network book catalog (`catalogo.php`), registration (`cadastrar_livro.php`), editing (`editar_livro.php`), and deletion (`excluir_livro.php`).
* `pagina_novo_emprestimo9/` — New loan registration form (`novo_emprestimo.php`).
* `pagina_registrar_devolucao10/` — Book return registration form (`registrar_devolucao.php`).
