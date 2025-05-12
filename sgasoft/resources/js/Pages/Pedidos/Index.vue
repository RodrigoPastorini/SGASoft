<script setup>
import { ref } from 'vue'
import { useForm, router, Link, Head } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const props = defineProps({
    pedidos: Array,
    fornecedores: Array
})

const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingPedido = ref(null)

const createForm = useForm({
    fornecedor_id: '',
    data: '',
    valor_total: '',
    observacao: '',
    status: 'pendente',
})

const editForm = useForm({
    id: '',
    observacao: '',
    status: 'pendente',
})

const submit = () => {
    createForm.post('/pedidos', {
        onSuccess: () => {
            createForm.reset()
            showCreateModal.value = false
        }
    })
}

const deletar = (id) => {
    if (confirm('Deseja mesmo excluir este pedido?')) {
        router.delete(`/pedidos/${id}`)
    }
}

const abrirEditar = (pedido) => {
    editingPedido.value = pedido
    editForm.id = pedido.id
    editForm.observacao = pedido.observacao || ''
    editForm.status = pedido.status
    showEditModal.value = true
}

const atualizar = () => {
    editForm.put(route('pedidos.update', editForm.id), {
        onSuccess: () => {
            showEditModal.value = false
        }
    })
}
</script>

<template>
    <Head title="Pedidos" />

    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                        <NavLink :href="route('fornecedores.index')" :active="route().current('fornecedores')">Fornecedores</NavLink>
                        <NavLink :href="route('produtos.index')" :active="route().current('produtos')">Produtos</NavLink>
                        <NavLink :href="route('pedidos.index')" :active="route().current('pedidos')">Pedidos</NavLink>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="ms-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white hover:text-gray-700">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10..." clip-rule="evenodd"/></svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Pedidos</h1>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="showCreateModal = true">Novo Pedido</button>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Fornecedor</th>
                <th class="border px-4 py-2">Data</th>
                <th class="border px-4 py-2">Valor Total</th>
                <th class="border px-4 py-2">Observacao</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2 text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="pedido in pedidos" :key="pedido.id">
                <td class="border px-4 py-2">{{ pedido.fornecedor?.nome ?? '—' }}</td>
                <td class="border px-4 py-2">{{ pedido.data }}</td>
                <td class="border px-4 py-2">R$ {{ Number(pedido.valor_total).toFixed(2) }}</td>
                <td class="border px-4 py-2 capitalize">{{ pedido.observacao }}</td>
                <td class="border px-4 py-2 capitalize">{{ pedido.status }}</td>
                <td class="border px-4 py-2 text-center space-x-2">
                    <button @click="abrirEditar(pedido)" class="text-blue-600 hover:underline">Editar</button>
                    <button @click="deletar(pedido.id)" class="text-red-600 hover:underline">Excluir</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal de Novo Pedido -->
    <div v-if="showCreateModal" class="fixed z-50 inset-0 bg-black bg-opacity-50 flex items-center justify-center" @keydown.escape.window="showCreateModal = false">
        <div class="bg-white rounded shadow-lg w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Novo Pedido</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Fornecedor</label>
                    <select v-model="createForm.fornecedor_id" class="mt-1 block w-full border border-gray-300 rounded p-2">
                        <option value="">Selecione um fornecedor</option>
                        <option v-for="f in fornecedores" :key="f.id" :value="f.id">{{ f.nome }}</option>
                    </select>
                    <div v-if="createForm.errors.fornecedor_id" class="text-red-600 text-sm mt-1">{{ createForm.errors.fornecedor_id }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Data</label>
                    <input v-model="createForm.data" type="date" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="createForm.errors.data" class="text-red-600 text-sm mt-1">{{ createForm.errors.data }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Valor Total</label>
                    <input v-model="createForm.valor_total" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="createForm.errors.valor_total" class="text-red-600 text-sm mt-1">{{ createForm.errors.valor_total }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Observação</label>
                    <textarea v-model="createForm.observacao" rows="3" class="mt-1 block w-full border border-gray-300 rounded p-2"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showCreateModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Editar Pedido -->
    <div v-if="showEditModal" class="fixed z-50 inset-0 bg-black bg-opacity-50 flex items-center justify-center" @keydown.escape.window="showEditModal = false">
        <div class="bg-white rounded shadow-lg w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Editar Pedido</h2>
            <form @submit.prevent="atualizar">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="editForm.status" class="mt-1 block w-full border border-gray-300 rounded p-2">
                        <option value="pendente">Pendente</option>
                        <option value="concluido">Concluido</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Observação</label>
                    <textarea v-model="editForm.observacao" rows="3" class="mt-1 block w-full border border-gray-300 rounded p-2"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEditModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</template>
