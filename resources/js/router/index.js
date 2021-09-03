import {createWebHistory, createRouter} from "vue-router";

import Books from '../pages/Books';
import Authors from '../pages/Authors';
import BookList from '../pages/BookList';
import AuthorStat from '../pages/AuthorStat'
import Login from '../pages/Login';

import StoreBook from '../pages/StoreBook';
import StoreAuthor from '../pages/StoreAuthor';

export const routes = [

    //routes for table with books
    {
        name: 'books',
        path: '/books',
        component: Books
    },

    //routes for table with authors
    {
        name: 'authors',
        path: '/authors',
        component: Authors
    },

    //route for auth page
    {
        name: 'login',
        path: '/login',
        component: Login
    },

    //route for list of books with each author
    {
        name: 'book-list',
        path: '/book-list',
        component: BookList
    },

    //route for books amount foreach author
    {
        name: 'author-stat',
        path: '/author-stat',
        component: AuthorStat
    },

    //route for saving new book
    {
        name: 'store-book',
        path: '/store-book',
        component: StoreBook
    },

    //route for updating existing book
    {
        name: 'update-book',
        path: '/update-book/:id',
        component: StoreBook
    },

    //route for updating existing author
    {
        name: 'update-author',
        path: '/update-author/:id',
        component: StoreAuthor
    },

    //route for storing new author
    {
        name: 'store-author',
        path: '/store-author',
        component: StoreAuthor
    },
];

//create Router
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
