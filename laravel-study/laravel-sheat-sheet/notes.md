DIferença entre GET.create e POST.store, Get.edit e PUT.update

Em suma, os Get.create e GET.edit é um GET para buscar os formalaros para fazer o POST e o PUT Respectivamente. Agora, se você só cria a API, ele sâo inúteis, apenas o store e udpdate vai precisar, pois o formulario serar preencido em outro lugar

The create method should return a view with a form.

The store method should handle the form and create the entity and redirect.

The edit method should return a view with a form with data from the entity.

The update method should handle the form and update the entity and redirect.

index mostra tudo; show busca por id
